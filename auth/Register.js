var password = document.getElementById("password");
var repassword = document.getElementById("re_password");
var regButton = document.getElementById("regButton");
var email = document.getElementById("email");
var email_error = document.getElementById("email_error");
var password_error = document.getElementById("password_error");
var passwordGood = false;
var emailGood = false;

email.addEventListener('input', async function()  {
    let check = email.value;
    await fetch(`emailCheck.php?email=${check}`)
        .then(response => response.text())
        .then(data => {
            console.log(data);
            if(data === "yes"){
                emailGood = true;
                email_error.style.display = "none"
            }
            else if(data === "no"){
                emailGood = false;
                email_error.style.display = "block";
            }
        })
})
password.addEventListener('input', ()=>{
    if(password.value !== repassword.value){
        passwordGood = false;
        password_error.style.display = "block"
    }
    else{
        passwordGood = true;
        password_error.style.display = "none"
    }
})
repassword.addEventListener('input', ()=>{
    if(password.value !== repassword.value){
        passwordGood = false;
        password_error.style.display = "block"
    }
    else{
        passwordGood = true;
        password_error.style.display = "none"
    }
})
regButton.addEventListener('click', (event)=>{
    event.preventDefault();
    console.log(passwordGood +" " +  emailGood);
    if(passwordGood && emailGood){
        document.getElementById("regForm").submit();
    }
})