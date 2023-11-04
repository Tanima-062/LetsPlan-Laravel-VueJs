<template>
    <!-- <span>
      <form class="bg-white shadow rounded-lg py-8 mx-auto login-form">
        <div  class="text-center">
            <img class="logo-login" src="/images/lets_play_logo.png"/>
            <p class="login-title text-center">Passwort zurücksetzen</p>
        </div>
          <template v-if="!show_success">
           <div class="space-bottom-15" >
                <label class="block mb-2 form-label" for="email">E-Mail</label>
                <input v-model="email" class="form-control form-input  w-full input-field" id="email" type="email"
                       name="email" required autofocus placeholder="E-Mail">
                <span class="text-danger"
                      v-if="errors && errors['email']">{{ errors['email'][0] }}</span>
            </div>
           <div class="space-bottom-15">
                <label class="block mb-2 form-label" for="password">Neues Kennwort</label>
                <input v-model="password" class="form-control form-input w-full  input-field" id="password" type="password"
                       name="password" required placeholder="Kennwort">
                <span class="text-danger"
                      v-if="errors && errors['password']">{{ errors['password'][0] }}</span>
            </div>
           <div class="space-bottom-15">
                <label class="block mb-2 form-label" for="password_confirmation">Neues Kennwort bestätigen</label>
                <input v-model="password_confirmation" class="form-control form-input w-full  input-field" id="password_confirmation" type="password"
                       name="password_confirmation" required placeholder="Neues Kennwort bestätigen">
                <span class="text-danger"
                      v-if="errors && errors['password_confirmation']">{{ errors['password_confirmation'][0] }}</span>
            </div>
            <button  @click.prevent="reset" type="submit" class="w-full btn btn-primary login-btn">
               Passwort zurücksetzen
            </button>
          </template>
           <div v-else class="success-message">
              <div>
              <p class="font-bold">Ihr Passwort wurde erfolgreich zurückgesetzt.</p>
              <p class="text-sm"> <router-link to="/login">Sie können sich ab sofort mit Ihrem neuen Passwort anmelden.</router-link></p>
            </div>
           </div>
    </form>
       <div class="text-center mt-6">
            <router-link to="/login" class="text-primary dim font-bold no-underline forgot-password">
               Zurück zur Anmeldung
            </router-link>
        </div>
  </span> -->
    <div class="login-parent">
        <div class="left-container">
            <img src="../../../../public/images/login.png" alt="login" />
        </div>
        <div class="right-container">
            <div class="header-img">
                <img src="../../../../public/images/new-logo.png" alt="logo" />
            </div>
            <div class="form-container">
                <div class="form">
                    <form v-if="!show_success">
                        <div class="space-bottom-15">
                            <label class="block mb-2 form-label" for="email"
                                >E-Mail*</label
                            >
                            <input
                                v-model="email"
                                class="form-control form-input w-full input-field"
                                id="email"
                                type="email"
                                name="email"
                                required
                                autofocus
                                placeholder="E-Mail Adresse eingeben
"
                            />
                            <span
                                class="text-danger"
                                v-if="errors && errors['email']"
                                >{{ errors["email"][0] }}</span
                            >
                        </div>

                        <div class="space-bottom-15">
                            <label class="block mb-2 form-label" for="password"
                                >Neues Passwort*
                                <!-- <span><img
                                        src="../../../../public/images/password.svg"
                                        alt="password"
                                        class="pass-icon"
                                    /></span> -->
                            </label>
                            <div class="input-icon">
                                <input
                                    v-model="password"
                                    class="form-control form-input w-full input-field"
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    placeholder="Passwort eingeben"
                                />
                                <img
                                    src="../../../../public/images/eye.svg"
                                    alt="eye"
                                    class="eye"
                                    @click="changeType('password')"
                                />
                            </div>
                            <span
                                class="text-danger"
                                v-if="errors && errors['password']"
                                >{{ errors["password"][0] }}</span
                            >
                        </div>

                        <div class="space-bottom-15">
                            <label
                                class="block mb-2 form-label"
                                for="password_confirmation"
                                >Neues Passwort bestätigen*</label
                            >
                            <div class="input-icon">
                                <input
                                    v-model="password_confirmation"
                                    class="form-control form-input w-full input-field"
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    placeholder="Neues Passwort bestätigen"
                                />
                                <img
                                    src="../../../../public/images/eye.svg"
                                    alt="eye"
                                    class="eye"
                                    @click="changeType('password_confirmation')"
                                />
                            </div>
                            <span
                                class="text-danger"
                                v-if="errors && errors['password_confirmation']"
                                >{{ errors["password_confirmation"][0] }}</span
                            >
                        </div>

                        <button
                            type="submit"
                            @click.prevent="reset"
                            class="login-btn"
                        >
                            Passwort zurücksetzen
                        </button>
                        <div v-if="show_success" class="success-message">
                            <p class="vielen-dank">
                                <span class="font-bold">Vielen Dank! </span>
                                <br />
                                Wir haben Ihnen eine E-Mail gesendet, mit
                                welcher
                                <br />
                                Sie Ihr Passwort zurücksetzen können.
                            </p>
                        </div>
                        <a @click="goToRegPage" class="pass-forgot"
                            >Zurück zur Anmeldung</a
                        >
                    </form>
                    <div v-if="show_success" class="success-message">
                        <h2>Passwort zurücksetzen</h2>
                        <p class="vielen-dank">
                            <span class="font-bold"
                                >Ihr Passwort wurde erfolgreich zurückgesetzt.
                            </span>
                            <br />
                            Sie können sich ab sofort mit Ihrem neuen Passwort
                            <br />
                            anmelden.
                        </p>

                        <a @click="goToRegPage" class="reg-page"
                            >Zurück zur Anmeldung</a
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
export default {
    name: "ResetPassword",
    mixins: [flushMessagesMixin],
    data() {
        return {
            token: "",
            email: "",
            password: "",
            password_confirmation: "",
            errors: [],
            show_success: false,
            // type: "password",
        };
    },
    created() {
        const urlParams = new URLSearchParams(window.location.search);
        this.email = urlParams.get("email");
        this.token = urlParams.get("token");
    },
    methods: {
        async reset() {
            try {
                this.errors = [];
                const res = await axios.post("/api/password/reset", {
                    email: this.email,
                    token: this.token,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                });
                this.show_success = true;
            } catch (err) {
                // this.errorMessage(err.response.data.message);
                this.errors = Object.assign({}, err.response.data.errors);
            }
        },
        goToRegPage() {
            this.$router.push("/login");
        },
        changeType(id) {
            let element = document.getElementById(id);
            element.type = element.type === "password" ? "text" : "Password";
            // this.type = this.type == "password" ? "text" : "password";
        },
    },
};
</script>

<style scoped lang="scss">
.success-message {
    h2 {
        font-family: "Ubuntu";
        font-style: normal;
        font-weight: 700;
        font-size: 35px;
        line-height: 40px;
        color: #e97d32;
        margin: 80px 0 40px;
    }
}

.pass-forgot {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 400;
    font-size: 12px;
    line-height: 18px;
    color: #a0a0a0;
    text-decoration: none;
    display: block;
    margin-top: 16px;
    cursor: pointer;
}

.reg-page {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 16px;
    line-height: 24px;
    color: #e97d32;
    margin-top: 56px;
    text-decoration: none;
    display: block;
    cursor: pointer;
}

.vielen-dank {
    font-family: "Nexa";
    font-style: normal;

    font-size: 16px;
    line-height: 24px;
    color: #2c5b7d;
    text-align: left;
}

.login-parent {
    display: flex;
    align-items: center;
    justify-content: center;
}

.left-container,
.right-container {
    height: 100vh;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
}

.left-container {
    width: 60%;
    margin: auto;
    background: #2c5b7d;

    img {
        width: 60%;
        object-fit: contain;
    }
}

.right-container {
    margin-top: -150px;
    width: 40%;
    background: #fff;

    .form-container {
        text-align: left;
        width: 70%;
        margin-top: 100px;

        .form {
            form label {
                font-family: "Nexa";
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                display: flex;
                align-items: center;
                color: #16a9b7;
            }

            form input {
                border-radius: 0px;
                border: none;
                background-color: #e9f7fe;
                width: 436px;
                padding: 10px 20px;
                height: 40px;
                font-family: "Nexa";
                position: relative;
            }

            form input:focus {
                background-color: #e9f7fe !important;
            }

            form input::placeholder {
                font-family: "Nexa";
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 23px;

                color: #87b1cb;
            }

            .login-btn {
                width: 436px;
                height: 40px;
                border-radius: 0px;
                margin-top: 25px;
                background: #e97d32;
                font-family: "Nexa";
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                text-align: center;
                color: #ffffff;
            }

            .pass-icon {
                object-fit: contain;
                margin-left: 5px;
                margin-top: -10px;
                width: 9px;
                height: 9px;
            }

            .input-icon {
                position: relative;
                width: 436px;

                .eye {
                    position: absolute;
                    right: 20px;
                    top: 10px;
                    width: 27px;
                    height: 19px;
                    object-fit: contain;
                    cursor: pointer;
                }
            }
        }
    }
}
</style>
