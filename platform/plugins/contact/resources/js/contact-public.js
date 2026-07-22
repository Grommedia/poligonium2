$(() => {
    let getCookie = function (name) {
        const value = `; ${document.cookie}`
        const parts = value.split(`; ${name}=`)

        if (parts.length === 2) {
            return decodeURIComponent(parts.pop().split(';').shift())
        }

        return ''
    }

    let ensureCsrfToken = function () {
        const existingToken = getCookie('XSRF-TOKEN')

        if (existingToken) {
            return $.Deferred().resolve(existingToken).promise()
        }

        return $.ajax({
            type: 'GET',
            cache: false,
            url: '/poligonium/csrf-token',
            dataType: 'json',
        }).then((response) => getCookie('XSRF-TOKEN') || response.token || '')
    }

    let showError = function (message) {
        $('.contact-error-message').html(message).show()
    }

    let showSuccess = function (message) {
        $('.contact-success-message').html(message).show()
    }

    let handleError = function (data) {
        if (typeof data.errors !== 'undefined' && data.errors.length) {
            handleValidationError(data.errors)
        } else {
            if (typeof data.responseJSON !== 'undefined') {
                if (typeof data.responseJSON.errors !== 'undefined') {
                    if (data.status === 422) {
                        handleValidationError(data.responseJSON.errors)
                    }
                } else if (typeof data.responseJSON.message !== 'undefined') {
                    showError(data.responseJSON.message)
                } else {
                    $.each(data.responseJSON, (index, el) => {
                        $.each(el, (key, item) => {
                            showError(item)
                        })
                    })
                }
            } else {
                showError(data.statusText)
            }
        }
    }

    let handleValidationError = function (errors) {
        let message = ''
        $.each(errors, (index, item) => {
            if (message !== '') {
                message += '<br />'
            }
            message += item
        })
        showError(message)
    }

    $(document).on('submit', '.contact-form', function (event) {
        event.preventDefault()
        event.stopPropagation()

        const $form = $(this)
        const $button = $form.find('button[type=submit]')

        $('.contact-success-message').html('').hide()
        $('.contact-error-message').html('').hide()

        $button.addClass('button-loading')

        ensureCsrfToken()
            .done((xsrfToken) => {
                const formData = new FormData($form[0])
                const headers = {}

                if (xsrfToken) {
                    formData.delete('_token')
                    headers['X-XSRF-TOKEN'] = xsrfToken
                }

                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: $form.prop('action'),
                    headers,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: ({ error, message }) => {
                        if (!error) {
                            $form[0].reset()
                            showSuccess(message)
                        } else {
                            showError(message)
                        }

                        if (typeof refreshRecaptcha !== 'undefined') {
                            refreshRecaptcha()
                        }

                        document.dispatchEvent(new CustomEvent('contact-form.submitted'))
                    },
                    error: (error) => {
                        if (typeof refreshRecaptcha !== 'undefined') {
                            refreshRecaptcha()
                        }
                        handleError(error)
                    },
                    complete: () => $button.removeClass('button-loading'),
                })
            })
            .fail((error) => {
                $button.removeClass('button-loading')
                handleError(error)
            })
    })
})
