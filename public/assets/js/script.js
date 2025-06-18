function showSidebarMobile() {
    const sidebar = document.getElementsByTagName('nav')[0];
    const content = document.getElementById('content');
    
    if (sidebar.classList.contains('mobile:hidden')) { 
        sidebar.classList.remove('mobile:hidden'); 
        content.classList.add('hidden');
    } 
    else { 
        sidebar.classList.add('mobile:hidden'); 
        content.classList.remove('hidden');
    }
}

function requestApi(method, func, fields = "", redirect = null) {
    let fieldsList = fields.split(':');
    let postData = "";
    if(fields) {
        let arr = {};
        $.each(fieldsList, function(k, v) {
            let el = $('#' + v);
            let type = el.attr('type');
            if(type === 'radio') { el = $('#' + v + ':checked'); }
            else if(type === 'checkbox') { arr[v] = el.is(':checked'); }
            else { arr[v] = el.val(); }

            $('#' + v + '-helpText').html('');
        });
        $('#global-message').html('').addClass('hidden');

        postData = JSON.stringify(arr);
        if(method == "GET") { postData = arr; }
        $.ajax({
            url: 'http://10.10.0.201:90/api/' + func,
            dataType: 'json',
            type: method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + getCookie('access_token')
            },
            data: postData,
            cache: false,
            success: function(r) { resolveApi(r, redirect); },
            error: function(r) { resolveApi(r, redirect); }
        })
    }
}

function resolveApi(r, redirect) {
    console.log(r);
    let data = r.responseJSON != undefined ? r.responseJSON : r;
    if(!data) { return }

    if(data.success) {
        if(data.api_key) {
            let date = new Date();
            date.setTime(date.getTime() + (24 * 60 * 60 * 1000 * 60));
            document.cookie = "access_token=" + data.api_key + ";expires=" + date.toUTCString() + ";path=/";
        }
        if(redirect) {
            window.location.href = redirect;
        }
    } else {
        if(data.errors) {
            $.each(data.errors, function(field, errors) {
                let text = "";
                $.each(errors, function(k, v) { text += v + '<br/>'; });
                $('#' + field + '-helpText').html(text).addClass('text-red-500');
            })
        } else if(data.message) {
            $('#global-message').html(data.message).removeClass('hidden');
        }
    }
}

function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}