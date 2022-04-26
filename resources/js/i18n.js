import VueI18n from 'vue-i18n'
import axios from 'axios'
import Vue from "vue";

Vue.use(VueI18n)

let lang=LANG_I18N
if (!localStorage.getItem('language')) {
    localStorage.setItem('language', lang);
} else {
    lang = localStorage.getItem("language");
}
export const i18n = new VueI18n({
    locale: lang,
    fallbackLocale: 'en',
});

(function loadLanguage() {
    axios.get(`../lang/${lang}.json`).then(
        function (messages) {
            i18n.locale = lang
            i18n.setLocaleMessage(lang, messages.data)
        }
    )
})();

function loadLanguageAsync(lang) {
    
    axios.get(`../lang/${lang}.json`).then(
        function (messages) {
            i18n.locale = lang
            i18n.setLocaleMessage(lang, messages.data)
            localStorage.setItem('language', lang);
            window.location=`${location.origin}${location.pathname}?lang=${lang}`
        }
    )
}

window.loadLanguageAsync = loadLanguageAsync;