<template>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1644088379091-d574269d422f?q=80&w=1393&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="max-w-lg w-full mx-auto p-8 bg-white bg-opacity-90 rounded-lg shadow-md relative z-10">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Reset Password
                </h1>
                <p class="text-gray-600">
                    Enter your new password
                </p>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <!-- New Password Input -->
                <div class="form-group relative">
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password"
                        placeholder="New Password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                        :class="{ 'border-red-500': errors.password }"
                    />
                    <!-- Show/Hide Password Icon -->
                    <span
                        class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                        @click="toggleShowPassword"
                    >
                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-gray-500"></i>
                    </span>
                    <ErrorMessage :errors="errors" field="password" />
                </div>

                <!-- Confirm Password Input -->
                <div class="form-group relative">
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password_confirmation"
                        placeholder="Confirm Password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                        :class="{ 'border-red-500': errors.password_confirmation }"
                    />
                    <ErrorMessage :errors="errors" field="password_confirmation" />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 transition-colors"
                    :disabled="isLoading"
                >
                    {{ isLoading ? 'Resetting...' : 'Reset Password' }}
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
    import { reactive, watch, ref } from "vue";
    import { useRoute, useRouter } from "vue-router";
    import { useAuthApi } from '../../composables/useAuthApi';
    import { useFormValidation } from "../../composables/useFormValidation";
    import ErrorMessage from "../../views/components/ErrorMessage.vue";

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

    const toggleShowPassword = () => {
        showPassword.value = !showPassword.value;
    };
    const handleSubmit = async () => {
        try {
            await handleResetPassword(
                email,
                token,
                form.password,
                form.password_confirmation
            );
        } catch (error) {
            if(error.status == 410) {
                router.push('/admin/forgot-password');
            }
        }
    };

    watch(error, (newError) => {
        if (newError) {
            handleApiError(newError);
        }
    });
</script>
