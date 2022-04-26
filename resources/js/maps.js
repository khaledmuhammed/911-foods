// open the main map  now
let main_map=document.getElementById('map');
let btn_open_main_map=document.getElementById('open_map');
let btn_open_main_map_phone=document.getElementById('open_map_phone');
if (btn_open_main_map) {
    btn_open_main_map.addEventListener('click',e=>{
        main_map.classList.toggle('hidden');
        if (btn_open_main_map.textContent==="Open Map") {
            btn_open_main_map.textContent="Colse Map";
        }else{
            btn_open_main_map.textContent="Open Map";
        }
    })
}
if (btn_open_main_map_phone) {
    btn_open_main_map_phone.addEventListener('click',e=>{
        main_map.classList.toggle('hidden');
    })
}	

