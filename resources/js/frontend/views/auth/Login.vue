<template>
    <div
        class="font-poppins bg-gradient-to-br from-indigo-50 to-purple-50 min-h-screen flex items-center justify-center p-4"
    >
        <div
            class="max-w-6xl w-full bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row"
        >
            <!-- Left Section - Branding & Features -->
            <div
                class="md:w-1/2 bg-[#4f46e5] text-white p-8 md:p-12 lg:p-16 relative overflow-hidden"
            >
                <div
                    class="absolute -top-20 -right-20 w-60 h-60 rounded-full bg-white/10"
                ></div>
                <div
                    class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-white/5"
                ></div>

                <div class="relative z-10">
                    <div class="flex items-center mb-10">
                        <div
                            class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-primary font-bold text-xl"
                        >
                            B
                        </div>
                        <span class="ml-3 text-2xl font-bold">Blogium</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold mb-4">
                        Welcome Back
                    </h1>
                    <p class="text-indigo-100 mb-10">
                        Sign in to continue your journey with our community
                    </p>

                    <ul class="space-y-5">
                        <li
                            v-for="feature in features"
                            :key="feature"
                            class="flex items-center"
                        >
                            <div
                                class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-4"
                            >
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <span>{{ feature }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Section - Login Form -->
            <div class="md:w-1/2 p-8 md:p-12 lg:p-16">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    Login to your account
                </h2>
                <p class="text-gray-600 mb-8">Enter your details to continue</p>

                <form @submit.prevent="handleAuth" class="space-y-6">
                    <!-- Email -->
                    <div class="relative">
                        <input
                            type="email"
                            v-model="form.login"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all"
                            placeholder="Email Address or Phone Number"
                        />
                        <ErrorMessage :errors="errors" field="login" />

                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            class="w-full px-4 pl-11 py-3 border border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all"
                            placeholder="Password"
                        />
                        <button
                            type="button"
                            @click="togglePasswordVisibility"
                            class="absolute right-4 top-4 text-gray-400 hover:text-primary"
                        >
                            <i
                                :class="
                                    showPassword
                                        ? 'far fa-eye-slash'
                                        : 'far fa-eye'
                                "
                            ></i>
                        </button>
                        <ErrorMessage :errors="errors" field="password" />

                    </div>

                    <!-- forgot -->
                    <div class="flex justify-between items-center">
                        <router-link
                            to="/forgot-password"
                            class="text-primary font-medium hover:underline"
                            >Forgot password?</router-link
                        >
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="w-full bg-primary text-white font-medium py-3 rounded-xl shadow-lg hover:bg-primary-dark transition-all disabled:bg-gray-300 disabled:text-gray-500"
                    >
                        <span v-if="!isLoading">Sign In</span>
                        <span v-else>
                            <i class="fas fa-spinner fa-spin mr-2"></i> Signing
                            in...
                        </span>
                    </button>

                    <!-- Divider -->
                    <div class="relative flex items-center justify-center my-6">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="flex-shrink mx-4 text-gray-500"
                            >Or continue with</span
                        >
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    <!-- Social -->
                    <!-- <div class="flex justify-center space-x-4">
                        <button
                            class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-[#DB4437] hover:text-white"
                        >
                            <i class="fab fa-google text-lg"></i>
                        </button>
                        <button
                            class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-[#4267B2] hover:text-white"
                        >
                            <i class="fab fa-facebook-f text-lg"></i>
                        </button>
                        <button
                            class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-[#1DA1F2] hover:text-white"
                        >
                            <i class="fab fa-twitter text-lg"></i>
                        </button>
                    </div> -->

                    <!-- Sign up -->
                    <p class="text-center text-gray-600 mt-8">
                        Don't have an account?
                        <router-link
                            to="/register"
                            class="text-primary font-medium hover:underline"
                            >Sign up now</router-link
                        >
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, reactive, watch } from "vue";
    import { useFormValidation } from "../../../admin/composables/useFormValidation";
    import { useAuthApi } from "../../../admin/composables/useAuthApi";
    import ErrorMessage from "../../../admin/views/components/ErrorMessage.vue";
    import { useRouter } from "vue-router";
    import { useUser } from "../../../admin/composables/useUser";

    // Initial form state
    const initialFormState = reactive({
        login: "",     // could be email or phone
        password: "",
    });

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);

    const { authenticate } = useAuthApi();
    const { login } = useUser();
    const router = useRouter();
    const { handleAuth, isLoading, error, response } = authenticate("/login", form, clearErrors, "You have successfully logged in.", '/');

    watch(error, (newError) => {
        if (newError) handleApiError(newError);
    });

    watch(response, (res) => {
        if (res?.data?.data?.token?.access_token && res?.data?.data?.user?.role) {
            login(res.data.data.token.access_token, res.data.data.user.role);
            router.push("/");
        }
    });

    const showPassword = ref(false);
        const togglePasswordVisibility = () => {
        showPassword.value = !showPassword.value;
    };

    // Features list (for UI rendering only)
    const features = [
        "Access to thousands of articles",
        "Personalized content recommendations",
        "Save your favorite posts",
        "Join the conversation with comments",
    ];
</script>
