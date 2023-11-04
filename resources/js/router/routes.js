import VueRouter from "vue-router";
import SupervisorRegistration from "../components/supervisor/SupervisorRegistration";
import Homepage from "../components/home/Homepage";
import EditSupervisor from "../components/supervisor/EditSupervisor";
import Profile from "../components/profile/Profile";
import Spielgruppen from "../components/spielgruppen/Spielgruppen";
import SpielgruppenCreate from "../components/spielgruppen/SpielgruppenCreate";
import SpielgruppenShow from "../components/spielgruppen/SpielgruppenShow";
import SpielgruppenEdit from "../components/spielgruppen/SpielgruppenEdit";
import Login from "../components/auth/Login";
import AdminLayout from "../components/layouts/AdminLayout";
import AuthLayout from "../components/layouts/AuthLayout";
import Anmeldungen from "../components/anmeldungen/Anmeldungen";
import ChildRegistration from "../components/client/ChildRegistration";
import ClientLayout from "../components/layouts/ClientLayout.vue";
import Plannung from "../components/plannung/Plannung";
import PlannungUserInfoCard from "../components/plannung/PlannungUserInfoCard";
import ConfirmationMessage from "../components/client/ConfirmationMessage.vue";
import OutOfRegistrationPeriod from "../components/client/OutOfRegistrationPeriod";
import ForgotPassword from "../components/auth/ForgotPassword";
import ResetPassword from "../components/auth/ResetPassword";
import Company from "../components/company/Company";
import AddNewCompany from "../components/company/AddNewCompany";
import EditCompany from "../components/company/EditCompany";
import Adminuser from "../components/adminuser/AdminUser";
import EditAdminuser from "../components/adminuser/EditAdminUser";
import AddNewUser from "../components/adminuser/AddNewUser";
import Division from "../components/division/Division";
import EditDivision from "../components/division/EditDivision";
import AddDivision from "../components/division/AddDivision.vue";
import DivisionDetails from "../components/division/DivisionDetails";
import UserCreate from "../components/user/Create.vue";
import UserEdit from "../components/user/Edit.vue";
import UserOverview from "../components/user/UserOverview.vue";
import UserDetail from "../components/user/ShowDetails.vue";
import AnmeldungenShow from "../components/anmeldungen/Show.vue";
import AnmeldungenEdit from "../components/anmeldungen/Edit.vue";
import AnmeldungenCreate from "../components/anmeldungen/Create.vue";
import ChildRegistrationError from "../components/client/Error.vue";

const routes = [
    {
        path: "/",
        name: "default",
        component: AdminLayout,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                name: "dashboard",
                path: "/",
                redirect: "/startseite",
            },
            {
                name: "startseite",
                path: "/startseite",
                component: Homepage,
            },
            {
                name: "user",
                path: "/user",
                // component: SupervisorRegistration,
                component: UserOverview,
            },
            {
                name: "user-create",
                path: "/user/create",
                component: UserCreate,
            },
            {
                name: "user_details",
                path: "/user/:id",
                // component: SupervisorRegistration,
                component: UserDetail,
            },
            {
                name: "user-edit",
                path: "/user/edit/:id",
                component: UserEdit,
            },
            {
                name: "anmeldungen",
                path: "/anmeldungen",
                component: Anmeldungen,
            },
            {
                name: "anmeldungen-create",
                path: "/anmeldungen/create",
                component: AnmeldungenCreate,
            },
            {
                name: "anmeldungen-show",
                path: "/anmeldungen/show/:id",
                component: AnmeldungenShow,
            },
            {
                name: "anmeldungen-edit",
                path: "/anmeldungen/edit/:id",
                component: AnmeldungenEdit,
            },
            {
                name: "profile",
                path: "/profile",
                component: Profile,
            },
            {
                name: "plannung",
                path: "/plannung",
                component: Plannung,
            },
            {
                name: "child_info",
                path: "/anmeldungen/:id",
                component: PlannungUserInfoCard,
            },
            {
                name: "spielgruppen",
                path: "/spielgruppen",
                component: Spielgruppen,
            },
            {
                name: "spielgruppen_show",
                path: "/spielgruppen/show/:id",
                component: SpielgruppenShow,
            },
            {
                name: "spielgruppen_create",
                path: "/spielgruppen/create",
                component: SpielgruppenCreate,
            },
            {
                name: "spielgruppen_edit",
                path: "/spielgruppen/edit/:id",
                component: SpielgruppenEdit,
            },
            {
                name: "company",
                path: "/company",
                component: Company,
            },
            {
                name: "company_edit",
                path: "/company/edit/:id",
                component: EditCompany,
            },
            {
                name: "company_add",
                path: "/company/add",
                component: AddNewCompany,
            },
            {
                name: "adminuser",
                path: "/adminuser",
                component: Adminuser,
            },
            {
                name: "adminuser_edit",
                path: "/adminuser/edit/:id",
                component: EditAdminuser,
            },
            {
                name: "adminuser_add",
                path: "/adminuser/add",
                component: AddNewUser,
            },
            {
                name: "division",
                path: "/division",
                component: Division,
            },
            {
                name: "division_edit",
                path: "/division/edit/:id",
                component: EditDivision,
            },

            {
                name: "division_add",
                path: "/division/add",
                component: AddDivision,
            },
            {
                name: "division_details",
                path: "/division/details/:id",
                component: DivisionDetails,
            },
        ],
    },
    {
        path: "/login",
        name: "login",
        component: AuthLayout,
        children: [
            {
                name: "login",
                path: "",
                component: Login,
            },
        ],
    },
    {
        path: "/forgot-password",
        name: "forgot-password",
        component: AuthLayout,
        children: [
            {
                name: "forgot-password",
                path: "",
                component: ForgotPassword,
            },
        ],
    },
    {
        path: "/api/password/reset",
        name: "reset-password",
        component: AuthLayout,
        children: [
            {
                name: "reset-password",
                path: "",
                component: ResetPassword,
            },
        ],
    },
    {
        path: "/:company_id/child-registration",
        name: "client",
        component: ClientLayout,
        children: [
            {
                name: "child-registration",
                path: "",
                component: ChildRegistration,
            },
            {
                name: "confirmation-message",
                path: "confirmation-message",
                component: ConfirmationMessage,
            },
            {
                name: "out-of-registration-period",
                path: "out-of-registration-period",
                component: OutOfRegistrationPeriod,
            },
            {
                name: "child-registration-error",
                path: "error",
                component: ChildRegistrationError,
            },
        ],
    },
];

const router = new VueRouter({
    routes,
    mode: "history",
});

const isAuthenticated = localStorage.getItem("token");

router.beforeEach((to, from, next) => {
    if (to.name === "login" && isAuthenticated) {
        next("/");
        return;
    }
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (isAuthenticated) {
            next();
            return;
        }
        next("/login");
    }
    next();
});
export default router;
