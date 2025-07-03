<template>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1498804103079-a6351b050096');">
        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="max-w-lg w-full mx-auto p-8 bg-white bg-opacity-90 rounded-lg shadow-md relative z-10">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Forgot Password?
                </h1>
                <p class="text-gray-600">
                    Enter your email to reset your password
                </p>
            </div>

            <form @submit.prevent="submitForgotPassword" class="space-y-4">
                <!-- Email Input -->
                <div class="form-group relative">
                    <input
                        type="email"
                        v-model="form.email"
                        placeholder="Email"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.email }"
                    />
                    <ErrorMessage :errors="errors" field="email" />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 transition-colors"
                    :disabled="isLoading"
                >
                    {{ isLoading ? 'Sending...' : 'Reset Password' }}
                </button>
            </form>

            <!-- Back to Login Link -->
            <p class="mt-4 text-center text-gray-600">
                Remember your password?
                <router-link to="/admin/login" class="text-blue-600 hover:text-blue-800">
                    Login
                </router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
    import { reactive, watch, ref  } from "vue";
    import { useFormValidation } from "@/composables/useFormValidation";
    import { useAuthApi } from '@/composables/useAuthApi';
    import ErrorMessage from "@/views/components/ErrorMessage.vue";

    const initialFormState  = reactive({
        email: "",
    });

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);
    const { handleForgotPassword, isLoading, error, message, messageClass } = useAuthApi();

    const submitForgotPassword = async () => {
        try {
            await handleForgotPassword (form.email);
        } catch (err) {
            console.error("Error:", err);
        }
    };

    watch(error, (newError) => {
        if (newError) {
            handleApiError(newError);
        }
    });
</script>
