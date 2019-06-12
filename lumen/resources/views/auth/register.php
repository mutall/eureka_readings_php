<!DOCTYPE html>
<html>
    <head>
        <title>REGISTER</title>
        <style>
            .container{
                width: 70%;
                margin: 100px auto;
                padding: 0 200px
            }
            .box{
                display: flex;
                height: auto;
                align-items: center;
                justify-content: space-around;
                border-style: dotted;
                border-color: #311B92;
                border-width: 1px;
            }

            input, .select{
                display: block;
                margin: 15px 0;
                width: 100%;
                height: 40px;
                border: 2px solid #311B92;
                border-radius: 20px;
                text-align: center;

            }
            .row{
                display: flex;
                flex-direction: row;

            }
            .row > *{
                margin: 10px 20px;
            }
            span{
                font-size: 20px;
                text-align: center;
                text-transform: uppercase;
                font-weight: 700;

            }

            .error{
                color: red;
            }
            .success{
                color: #AED581;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="box">
                <form method="post" action="/<?php echo env('SUB_FOLDER'); ?>/auth/register">
                    <input type="text" name="username" placeholder="Enter username" required/>
                    <input type="text" name="fullname" placeholder="Enter full name" required/>
                    <input type="email" name="email" placeholder="Enter email" />
                    <span id="password-error" class=""></span>
                    <div class="row">
                        <input type="password" name="password" placeholder="Enter password" required/>
                        <input type="password"  onkeyup="confirm(this.value)" id="confirm-password" placeholder="Confirm password" required/>
                    </div>
                    <div class="row">
                        <input type="number" name="number" placeholder="Enter mobile number" required/>
                        <input type="text"   name="model" placeholder="Confirm phone model" required/>
                    </div>
                    <select name="type" class="select">
                        <option>reader</option>
                        <option>admin</option>
                    </select>
                    <div class="row">
                        <input type="submit" id="submit" value="REGISTER"/>
                        <input type="reset" value="CLEAR"
                    </div>

                </form>
            </div>
        </div>
        <script>
            const confirm = (val) => {
                const input_pass = document.getElementsByName("password")[0].value;
                const span = document.querySelector("#password-error");
                const submit = document.querySelector('#submit');

                if (input_pass !== "") {
                    let res = input_pass.localeCompare(val);
                    if (res !== 0) {
                        submit.disabled = true;
                        span.removeAttribute('class');
                        span.setAttribute('class', 'error');
                        span.innerHTML = "Passwords dont match";
                    } else {

                        span.removeAttribute('class');
                        span.setAttribute('class', 'success');
                        submit.disabled = false;
                        span.innerHTML = "Passwords match";

                    }
                }

            };
        </script>
    </body>
</html>