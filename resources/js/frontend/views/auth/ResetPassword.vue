<!-- ResetPassword.vue -->
<template>
    <div
        class="font-poppins bg-gradient-to-br from-indigo-50 to-purple-50 min-h-screen flex items-center justify-center p-4"
    >
        <div
            class="max-w-6xl w-full bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row"
        >
            <!-- Left Section -->
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
                        Reset your password
                    </h1>
                    <p class="text-indigo-100 mb-10">
                        Set a new password to secure your account again.
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

            <!-- Right Section -->
            <div class="md:w-1/2 p-8 md:p-12 lg:p-16">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    Create a new password
                </h2>
                <p class="text-gray-600 mb-8">Enter your new password below</p>

                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <!-- New Password -->
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            class="w-full px-4 pl-11 py-3 border border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all"
                            placeholder="New Password"
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

                    <!-- Confirm Password -->
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password_confirmation"
                            class="w-full px-4 pl-11 py-3 border border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all"
                            placeholder="Confirm Password"
                        />
                        <ErrorMessage
                            :errors="errors"
                            field="password_confirmation"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="w-full bg-primary text-white font-medium py-3 rounded-xl shadow-lg hover:bg-primary-dark transition-all disabled:bg-gray-300 disabled:text-gray-500"
                    >
                        <span v-if="!isLoading">Reset Password</span>
                        <span v-else
                            ><i class="fas fa-spinner fa-spin mr-2"></i>
                            Resetting...</span
                        >
                    </button>

                    <p class="text-center text-gray-600 mt-8">
                        Go back to
                        <router-link
                            to="/login"
                            class="text-primary font-medium hover:underline"
                            >Login</router-link
                        >
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { reactive, watch, ref } from "vue";
    import { useRoute, useRouter } from "vue-router";
    import { useAuthApi } from '../../../admin/composables/useAuthApi';
    import { useFormValidation } from "../../../admin/composables/useFormValidation";
    import ErrorMessage from "../../../admin/views/components/ErrorMessage.vue";

    const initialFormState = reactive({
        password: "",
        password_confirmation: ""
    });

    const route = useRoute();
    const router = useRouter();
    const { handleResetPassword, isLoading, error } = useAuthApi();
    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);

    const email = route.query.email;
    const token = route.query.token;
    const showPassword = ref(false);

    const togglePasswordVisibility = () => {
        showPassword.value = !showPassword.value;
    };

    const handleSubmit = async () => {
        try {
            await handleResetPassword(
                email,
                token,
                form.password,
                form.password_confirmation,
                '/login'
            );
        } catch (error) {
            if(error.status == 410) {
                router.push('/forgot-password');
            }
        }
    };

    watch(error, (newError) => {
        if (newError) {
            handleApiError(newError);
        }
    });

    const features = [
        "Strong password security",
        "Quick reset process",
        "Privacy-first encryption",
        "Protect your data",
    ];
</script>
