function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

$('#send-feedback').click(function(){
     name = $("#your-name").val();
     email = $("#your-email").val();
     phone = $("#your-phone").val();
     subject = $("#your-subject").val();
     msg = $("#your-msg").val();

     var error = null;

     if(name == '' || name.length < 2) 
          error = "Имя - обязательно и должно содержать от 2 символов"
     else if(!validateEmail(email))
          error = "Некорректный email"
     else if(phone.length < 2 || phone.length > 13) 
          error = "Длина телефона должна содержать от 2 до 13 символов"
     else if(subject.length < 2) 
          error = "Тема должна содержать от 2 символов"
     else if(msg.length == '' || msg.length < 2)
          error = "Сообщение - обязательно и должно содержать от 2 символов"

     if(error) return document.getElementById('result').innerHTML = '<div class="alert alert-danger">'+error+'</div>';
     else{
          $.ajax({
               url: '/contacts',
               method: 'POST',
               data: {
                    'name': name,
                    'email': email,
                    'phone': phone,
                    'subject': subject,
                    'msg': msg
               },
               headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
               },
               success: function(res)
               {
                    document.getElementById('result').innerHTML = res;
                    document.getElementById('contact_form').reset();
               }
          });
     }
});