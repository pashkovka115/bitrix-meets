const integration_name = BX('input-name')
const integration_activity = BX('input-activity')
const integration_ref = BX('input-inegrationref')
const integration_login = BX('input-login')
const integration_password = BX('input-password')
const button = BX('my-button')

new BX.bind(button, 'click', () => {
    document.getElementById('errors').innerHTML = ''
    var request = BX.ajax.runAction('ylab:meetings.api.integrationcontroller.add', {
        data: {
            fields: {
                'NAME': integration_name.value,
                'ACTIVITY': integration_activity.value,
                'INTEGRATION_REF': integration_ref.value,
                'LOGIN': integration_login.value,
                'PASSWORD': integration_password.value
            }
        }
    });
    request.then(function (response) {
        if (!response['data']['IS_SUCCESS']) {
            var errors = response['data']['ERROR_MESSAGES']
            for (var i in errors) {
                document.getElementById('errors').innerHTML +=
                    '<p><span style=\"color: red; \">' + errors[i] + '</span> </p>'
            }
        }
    })
})