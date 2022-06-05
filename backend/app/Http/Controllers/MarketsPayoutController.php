<?php

namespace App\Http\Controllers;

use Flash;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Market;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use App\Models\MarketsPayout;
use Illuminate\Support\Facades\Log;
use App\Repositories\MarketRepository;
use App\Repositories\EarningRepository;
use Illuminate\Support\Facades\Response;
use App\DataTables\MarketsPayoutDataTable;
use App\Repositories\CustomFieldRepository;
use App\Repositories\MarketsPayoutRepository;
use App\Http\Requests\CreateMarketsPayoutRequest;
use App\Http\Requests\UpdateMarketsPayoutRequest;
use App\Criteria\Earnings\EarningOfMarketCriteria;
use App\Criteria\Markets\MarketsOfManagerCriteria;
use Prettus\Validator\Exceptions\ValidatorException;

class MarketsPayoutController extends Controller
{
    /** @var  MarketsPayoutRepository */
    private $marketsPayoutRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var MarketRepository
     */
    private $marketRepository;
    /**
     * @var EarningRepository
     */
    private $earningRepository;

    public function __construct(MarketsPayoutRepository $marketsPayoutRepo, CustomFieldRepository $customFieldRepo, MarketRepository $marketRepo, EarningRepository $earningRepository)
    {
        parent::__construct();
        $this->marketsPayoutRepository = $marketsPayoutRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->marketRepository = $marketRepo;
        $this->earningRepository = $earningRepository;
    }

    /**
     * Display a listing of the MarketsPayout.
     *
     * @param MarketsPayoutDataTable $marketsPayoutDataTable
     * @return Response
     */
    public function index(MarketsPayoutDataTable $marketsPayoutDataTable)
    {
        return $marketsPayoutDataTable->render('markets_payouts.index');
    }

    /**
     * Show the form for creating a new MarketsPayout.
     *
     * @return Response
     */
    public function create()
    {

        if(auth()->user()->hasRole('manager')){
            $this->marketRepository->pushCriteria(new MarketsOfManagerCriteria(auth()->id()));
        }
        $market = $this->marketRepository->pluck('name', 'id');

        $hasCustomField = in_array($this->marketsPayoutRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->marketsPayoutRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('markets_payouts.create')->with("customFields", isset($html) ? $html : false)->with("market", $market);
    }

    /**
     * Store a newly created MarketsPayout in storage.
     *
     * @param CreateMarketsPayoutRequest $request
     *
     * @return Response
     */
    public function store(CreateMarketsPayoutRequest $request)
    {
        $input = $request->all();
        $earning = $this->earningRepository->findByField('market_id',$input['market_id'])->first();
        if($input['amount'] > $earning->market_earning){
            Flash::error('The payout amount must be less than market earning');
            return redirect(route('marketsPayouts.create'))->withInput($input);
        }
        $input['paid_date'] = Carbon::now();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->marketsPayoutRepository->model());
        try {
            $this->earningRepository->update([
                'total_earning'=> $earning->total_earning - $input['amount'] ,
                'market_earning' => ($earning->market_earning - $input['amount']),
                'vaild_earning' => ($earning->vaild_earning - $input['amount']),
                'admin_earning' => 0,
                'tax' => 0,
                'delivery_fee' => 0
            ], $earning->id);
            // $this->earningRepository->update(['market_earning'=>$earning->market_earning - $input['amount']], $earning->id);
            // $this->earningRepository->update(['vaild_earning'=>$earning->market_earning - $input['amount']], $earning->id);
            // $this->earningRepository->update(['admin_earning'=> 0], $earning->id);
            // $this->earningRepository->update(['delivery_fee'=> 0], $earning->id);
            // $this->earningRepository->update(['tax'=> 0], $earning->id);
            $marketsPayout = $this->marketsPayoutRepository->create($input);
            $marketsPayout->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.markets_payout')]));

        return redirect(route('marketsPayouts.index'));
    }

    /**
     * Display the specified MarketsPayout.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $marketsPayout = $this->marketsPayoutRepository->findWithoutFail($id);

        if (empty($marketsPayout)) {
            Flash::error('Markets Payout not found');

            return redirect(route('marketsPayouts.index'));
        }

        
        return view('markets_payouts.show')->with('marketsPayout', $marketsPayout);
    }
    /**
     * Display the specified MarketsPayout.
     *
     * @param int $id
     *
     * @return Response
     */
    public function view($id)
    {
        // $id is for payout id
        $marketsPayout = $this->marketsPayoutRepository->findWithoutFail($id);
        
        if (empty($marketsPayout)) {
            Flash::error('Markets Payout not found');

            return redirect(route('marketsPayouts.index'));
        }



        // get orders related to the payout date
        $market_id = $marketsPayout['market_id'];
        $end_date =  $marketsPayout['paid_date']->toDateTimeString();
        $start_date = null;
        $last_payout = MarketsPayout::where('market_id', $market_id)->where('paid_date', '<', $end_date)->latest('paid_date')->first();
        // dd($last_payout);
        if($last_payout){
            $start_date = $last_payout['paid_date']->toDateTimeString();
        }
        $market = Market::where('id', $marketsPayout['market_id'])->get()[0];
        $orders = [];
        foreach ($market->products as $product) {
            $start_date = $start_date == null ? '2022-01-01' : $start_date;

            $ProductOrders = ProductOrder::where('product_id', $product->id)->where('updated_at','>', $start_date)->where('updated_at', '<', $end_date)->with('order')->get();
            // dd($ProductOrders);
            foreach ($ProductOrders as $ProductOrder) {
                $order_date = $ProductOrder->order->updated_at->toDateTimeString();
                // dd($start_date.'|||||'. $order_date.'||||'. $end_date);
                // case only one payout
                if($start_date == null){
                    if($end_date > $order_date){
                        $ProductOrder->order['delivery_fee'] = $market['delivery_fee'];
                        $ProductOrder->order['sub_total'] = $ProductOrder['quantity'] * $ProductOrder['price'];
                        $ProductOrder->order['tax'] = $market['admin_commission'];
                        $ProductOrder->order['tax'] = $ProductOrder->order['sub_total'] * $ProductOrder->order['tax'] / 100;
                        $ProductOrder->order['total'] = $ProductOrder->order['sub_total'] + $ProductOrder->order['tax'] + $ProductOrder->order['delivery_fee'];
                        array_push($orders, $ProductOrder->order);
                    }
                }
                if($start_date < $order_date && $end_date > $order_date  ){
                    $ProductOrder->order['delivery_fee'] = $market['delivery_fee'];
                    $ProductOrder->order['sub_total'] = $ProductOrder['quantity'] * $ProductOrder['price'];
                    $ProductOrder->order['tax'] = $market['admin_commission'];
                    $ProductOrder->order['tax'] = $ProductOrder->order['sub_total'] * $ProductOrder->order['tax'] / 100;
                    $ProductOrder->order['total'] = $ProductOrder->order['sub_total'] + $ProductOrder->order['tax'] + $ProductOrder->order['delivery_fee'];
                    array_push($orders, $ProductOrder->order);
                }
            }
        }
        // dd($orders);
     
        return view('markets_payouts.show')->with([
            'marketsPayout' => $marketsPayout,
            'orders' => $orders
        ]);

    }

    /**
     * Show the form for editing the specified MarketsPayout.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $marketsPayout = $this->marketsPayoutRepository->findWithoutFail($id);
        $market = $this->marketRepository->pluck('name', 'id');


        if (empty($marketsPayout)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.markets_payout')]));

            return redirect(route('marketsPayouts.index'));
        }
        $customFieldsValues = $marketsPayout->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->marketsPayoutRepository->model());
        $hasCustomField = in_array($this->marketsPayoutRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('markets_payouts.edit')->with('marketsPayout', $marketsPayout)->with("customFields", isset($html) ? $html : false)->with("market", $market);
    }

    /**
     * Update the specified MarketsPayout in storage.
     *
     * @param int $id
     * @param UpdateMarketsPayoutRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMarketsPayoutRequest $request)
    {
        $marketsPayout = $this->marketsPayoutRepository->findWithoutFail($id);

        if (empty($marketsPayout)) {
            Flash::error('Markets Payout not found');
            return redirect(route('marketsPayouts.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->marketsPayoutRepository->model());
        try {
            $marketsPayout = $this->marketsPayoutRepository->update($input, $id);


            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $marketsPayout->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.markets_payout')]));

        return redirect(route('marketsPayouts.index'));
    }

    /**
     * Remove the specified MarketsPayout from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $marketsPayout = $this->marketsPayoutRepository->findWithoutFail($id);

        if (empty($marketsPayout)) {
            Flash::error('Markets Payout not found');

            return redirect(route('marketsPayouts.index'));
        }

        $this->marketsPayoutRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.markets_payout')]));

        return redirect(route('marketsPayouts.index'));
    }

    /**
     * Remove Media of MarketsPayout
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $marketsPayout = $this->marketsPayoutRepository->findWithoutFail($input['id']);
        try {
            if ($marketsPayout->hasMedia($input['collection'])) {
                $marketsPayout->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
