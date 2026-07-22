import { axios, HttpClient } from './utilities'

window._ = require('lodash')

window.axios = axios

window.$httpClient = new HttpClient()

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
})

window.BotbleRedirectToAdminLogin = (payload = {}) => {
    if (window.BotbleIsRedirectingToAdminLogin) {
        return
    }

    window.BotbleIsRedirectingToAdminLogin = true

    const loginUrl = payload.login_url || window.adminLoginUrl || `${window.location.origin}/admin/login`
    const redirectUrl = new URL(loginUrl, window.location.origin)

    if (!redirectUrl.searchParams.has('back')) {
        redirectUrl.searchParams.set('back', window.location.href)
    }

    Botble.showError('Сессия администратора истекла. Перехожу на вход...')

    setTimeout(() => {
        window.location.href = redirectUrl.toString()
    }, 800)
}

$(document).ajaxError((event, xhr) => {
    const response = xhr.responseJSON || {}
    const message = response.message || xhr.responseText || ''
    const statusCode = Number(xhr.status || response.code || 0)
    const isSessionExpired =
        statusCode === 401 ||
        statusCode === 419 ||
        /csrf token mismatch|session expired|unauthenticated/i.test(message)

    if (isSessionExpired) {
        window.BotbleRedirectToAdminLogin(response.additional || response)
    }
})

$(() => {
    setTimeout(() => {
        if (typeof siteAuthorizedUrl === 'undefined' || typeof isAuthenticated === 'undefined' || !isAuthenticated) {
            return
        }

        const $reminder = $('[data-bb-toggle="authorized-reminder"]')

        if ($reminder.length) {
            return
        }

        $httpClient
            .makeWithoutErrorHandler()
            .get(siteAuthorizedUrl, { verified: true })
            .then(() => null)
            .catch((error) => {
                if (!error.response || error.response.status !== 200) {
                    return
                }

                $(error.response.data.data.html).prependTo('body')
                $(document).find('.alert-license').slideDown()
            })
    }, 1000)
})
