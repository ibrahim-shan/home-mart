<template>
    <div v-if="theme === 'loading'">
        <LoadingComponent :props="{isActive:true}" />
    </div>

    <div v-if="theme === 'frontend'">
        <FrontendNavbarComponent />
        <FrontendCartComponent />
        <router-view></router-view>
        <a v-if="whatsappLink" :href="whatsappLink" target="_blank" rel="noopener noreferrer"
            class="fixed bottom-5 z-50 flex items-center justify-center w-12 h-12 rounded-full shadow-lg bg-[#25D366] text-white transition hover:bg-[#1EBE5D] ltr:right-5 rtl:left-5"
            aria-label="WhatsApp">
            <svg viewBox="0 0 32 32" class="w-7 h-7 fill-white" aria-hidden="true">
                <path
                    d="M19.11 17.45c-.25-.12-1.49-.74-1.72-.82-.23-.09-.4-.12-.57.12-.17.25-.65.82-.8.98-.15.17-.3.19-.55.06-.25-.12-1.06-.39-2.02-1.25-.75-.67-1.25-1.5-1.4-1.75-.15-.25-.02-.38.11-.5.11-.11.25-.3.37-.45.12-.15.17-.25.25-.42.08-.17.04-.32-.02-.45-.06-.12-.57-1.38-.78-1.89-.2-.48-.41-.41-.57-.42-.15 0-.32 0-.49 0-.17 0-.45.06-.69.32-.24.25-.91.89-.91 2.17 0 1.27.93 2.49 1.06 2.66.12.17 1.83 2.8 4.43 3.92.62.27 1.1.43 1.48.55.62.2 1.18.17 1.62.1.49-.07 1.49-.61 1.7-1.2.21-.59.21-1.1.15-1.2-.06-.1-.23-.17-.48-.3ZM16 5.33c-5.89 0-10.67 4.78-10.67 10.67 0 1.88.49 3.71 1.42 5.33L5.33 26.67l5.47-1.37c1.58.86 3.36 1.31 5.2 1.31 5.89 0 10.67-4.78 10.67-10.67S21.89 5.33 16 5.33Zm0 19.33c-1.65 0-3.26-.45-4.67-1.29l-.34-.2-3.25.81.86-3.17-.22-.33a8.61 8.61 0 0 1-1.39-4.68c0-4.77 3.88-8.64 8.64-8.64 4.77 0 8.64 3.88 8.64 8.64 0 4.77-3.88 8.64-8.64 8.64Z" />
            </svg>
        </a>
        <FrontendMobileSideBarComponent />
        <FrontendMobileNavBarComponent />
        <FrontendMobileCategoryComponent />
        <FrontendMobileAccountComponent />
        <FrontendCookiesComponent />
        <FrontendFooterComponent />
    </div>

    <div v-if="theme === 'backend'">
        <main class="db-main" v-if="logged">
            <BackendNavbarComponent />
            <BackendMenuComponent />
            <router-view></router-view>
        </main>
        <div v-if="!logged">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import BackendNavbarComponent from "./layouts/backend/BackendNavbarComponent";
import BackendMenuComponent from "./layouts/backend/BackendMenuComponent";
import FrontendNavbarComponent from "./layouts/frontend/FrontendNavBarComponent";
import FrontendFooterComponent from "./layouts/frontend/FrontendFooterComponent";
import FrontendCartComponent from "./layouts/frontend/FrontendCartComponent";
import FrontendMobileNavBarComponent from "./layouts/frontend/FrontendMobileNavBarComponent";
import FrontendMobileCategoryComponent from "./layouts/frontend/FrontendMobileCategoryComponent";
import FrontendMobileAccountComponent from "./layouts/frontend/FrontendMobileAccountComponent";
import FrontendMobileSideBarComponent from "./layouts/frontend/FrontendMobileSideBarComponent";
import FrontendCookiesComponent from "./layouts/frontend/FrontendCookiesComponent";
import DisplayModeEnum from "../enums/modules/displayModeEnum";
import env from "../config/env";
import LoadingComponent from "../components/frontend/components/LoadingComponent.vue";

export default {
    name: "DefaultComponent",
    components: {
        FrontendMobileSideBarComponent,
        FrontendMobileAccountComponent,
        FrontendMobileCategoryComponent,
        FrontendMobileNavBarComponent,
        FrontendCartComponent,
        FrontendNavbarComponent,
        FrontendFooterComponent,
        BackendNavbarComponent,
        BackendMenuComponent,
        FrontendCookiesComponent,
        LoadingComponent
    },
    data() {
        return {
            theme: "loading",
        }
    },
    beforeMount() {
        this.displayModeDefine();
        this.$store.dispatch('frontendSetting/lists').then(res => {
            this.$store.dispatch("globalState/init", {
                language_id: res.data.data.site_default_language,
                search_restaurant: "",
                location: null,
                latitude: null,
                longitude: null
            });
        }).catch();

        if (env.DEMO === "true" || env.DEMO === true || env.DEMO === "1" || env.DEMO === 1) {
            this.$store.dispatch("authcheck").then(res => {
                if (res.data.status === false) {
                    this.$router.push({ name: "frontend.home" });
                };
            }).catch();
        }
    },
    computed: {
        logged: function () {
            return this.$store.getters.authStatus;
        },
        displayMode: function () {
            return this.$store.getters['globalState/lists'].display_mode;
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        whatsappLink: function () {
            const callingCode = this.setting?.company_calling_code || "";
            const phone = this.setting?.company_phone || "";
            const number = `${callingCode}${phone}`.replace(/\D/g, "");
            if (!number) {
                return "";
            }
            const rawMessage = this.setting?.social_media_whatsapp_message || env.WHATSAPP_MESSAGE || "";
            const message = rawMessage ? encodeURIComponent(rawMessage) : "";
            return message
                ? `https://wa.me/${number}?text=${message}`
                : `https://wa.me/${number}`;
        }
    },
    methods: {
        displayModeDefine: function () {
            let dir = "ltr";
            const attributes = {
                dir: "ltr",
            };
            if (this.$store.getters['globalState/lists'].display_mode === DisplayModeEnum.LTR) {
                dir = "ltr";
            } else {
                dir = "rtl";
            }
            Object.keys(attributes).forEach(attr => {
                document.documentElement.setAttribute(attr, dir);
            });
        }
    },

    watch: {
        $route(e) {
            if (e.meta.isFrontend === true) {
                this.theme = "frontend";
            } else {
                this.theme = "backend";
            }
        },
        displayMode() {
            this.displayModeDefine();
        }
    },
}
</script>
