<template>
    <!-- <span>
      <form class="bg-white shadow rounded-lg py-8 mx-auto login-form">
        <div  class="text-center">
            <img class="logo-login" src="/images/lets_play_logo.png"/>
            <p class="login-title text-center">Passwort zurücksetzen</p>
        </div>
            <div class="space-bottom-15" v-if="!show_success">
                <label class="block mb-2 form-label" for="email">E-Mail</label>
                <input v-model="email" class="form-control form-input  w-full input-field" id="email" type="email"
                       name="email" required autofocus placeholder="Ihre E-Mail Adresse eingeben">
                <span class="text-danger"
                      v-if="errors && errors['email']">{{ errors['email'][0] }}</span>
            </div>
            <button v-if="!show_success" @click.prevent="reset" type="submit" class="w-full btn btn-primary login-btn">
                Passwort zurücksetzen
            </button>
           <div v-if="show_success" class="success-message">
              <p class="font-bold">Vielen Dank!</p>
              <p class="text-sm">Wir haben Ihnen eine E-Mail gesendet mit welcher Sie Ihr Passwort zurücksetzen können</p>
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
                <h2>Passwort zurücksetzen</h2>
                <div class="form">
                    <form>
                        <div class="space-bottom-15" v-if="!show_success">
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

                        <button
                            v-if="!show_success"
                            @click.prevent="reset"
                            type="submit"
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
                    </form>
                    <a @click="goToRegistration" class="reg-page"
                        >Zurück zur Anmeldung</a
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";
// import flushMessagesMixin from "../../mixins/flushMessagesMixin";
export default {
    name: "ForgotPassword",
    // mixins: [flushMessagesMixin],
    data() {
        return {
            email: "",
            errors: [],
            show_success: false,
        };
    },
    methods: {
        async reset() {
            try {
                this.errors = [];
                const res = await axios.post("/api/password/email", {
                    email: this.email,
                });
                this.show_success = true;
            } catch (err) {
                console.log(err);
                // this.errorMessage(err.response.data.message);
                this.errors = Object.assign({}, err.response.data.errors);
            }
        },
        goToRegistration() {
            this.$router.push("/login");
        },
    },
};
</script>

<style scoped lang="scss">
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
    margin-top: -200px;
    width: 40%;
    background: #fff;
    .form-container {
        text-align: left;
        width: 70%;
        h2 {
            font-family: "Ubuntu";
            font-style: normal;
            font-weight: 700;
            font-size: 35px;
            line-height: 40px;
            color: #e97d32;
            margin: 80px 0 40px;
        }
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
        }
    }
}
</style>
