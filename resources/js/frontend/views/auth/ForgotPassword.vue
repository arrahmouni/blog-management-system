<!-- ForgotPassword.vue -->
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
                        Forgot your password?
                    </h1>
                    <p class="text-indigo-100 mb-10">
                        No worries! We’ll help you reset it and get you back on
                        track.
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
                    Reset your password
                </h2>
                <p class="text-gray-600 mb-8">
                    Enter your email address to receive a password reset link
                </p>

                <form @submit.prevent="submitForgotPassword" class="space-y-6">
                    <div class="relative">
                        <input
                            type="email"
                            v-model="form.email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all"
                            placeholder="Email Address"
                        />
                        <ErrorMessage :errors="errors" field="email" />
                    </div>

                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="w-full bg-primary text-white font-medium py-3 rounded-xl shadow-lg hover:bg-primary-dark transition-all disabled:bg-gray-300 disabled:text-gray-500"
                    >
                        <span v-if="!isLoading">Send Reset Link</span>
                        <span v-else
                            ><i class="fas fa-spinner fa-spin mr-2"></i>
                            Sending...</span
                        >
                    </button>

                    <p class="text-center text-gray-600 mt-8">
                        Remembered your password?
                        <router-link
                            to="/login"
                            class="text-primary font-medium hover:underline"
                            >Sign in</router-link
                        >
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { reactive, watch, ref  } from "vue";
    import { useFormValidation } from "../../../admin/composables/useFormValidation";
    import { useAuthApi } from '../../../admin/composables/useAuthApi';
    import ErrorMessage from "../../../admin/views/components/ErrorMessage.vue";

    const initialFormState  = reactive({
        email: "",
    });

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);
    const { handleForgotPassword, isLoading, error, message, messageClass } = useAuthApi();

    const submitForgotPassword = async () => {
        try {
            await handleForgotPassword (form.email, '/login');
        } catch (err) {
            console.error("Error:", err);
        }
    };

    watch(error, (newError) => {
        if (newError) {
            handleApiError(newError);
        }
    });

    const features = [
        "Access to thousands of articles",
        "Personalized content recommendations",
        "Save your favorite posts",
        "Join the conversation with comments",
    ];
</script>
