// import {createI18n} from 'vue-i18n/index.mjs'
import { createI18n } from 'vue-i18n'
import R from '@/plugins/http'

const language = R.getLocalesName()

const messages = loadLocaleMessages()

const i18n = createI18n({
    fallbackLocale: 'zh-cn',
    globalInjection:true,
    legacy: false, // you must specify 'legacy: false' option
    locale: language,
    messages,
})

function loadLocaleMessages () {
    const locales = require.context('./langs', true, /[A-Za-z0-9-_,\s]+\.json$/i)
    const messages = {}
    for (const key of locales.keys()) {
      const matched = key.match(/([A-Za-z0-9-_]+)\./i)
      if (matched && matched.length > 1) {
        const locale = matched[1]
        messages[locale] = {
          ...locales(key),
        }
      }
    }
    return messages
}

export default i18n