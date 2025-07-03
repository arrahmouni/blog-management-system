<template>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1498804103079-a6351b050096');">
        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="max-w-lg w-full mx-auto p-8 bg-white bg-opacity-90 rounded-lg shadow-md relative z-10">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Create an Account
                </h1>
                <p class="text-gray-600">
                    Please fill in the details to register
                </p>
            </div>

            <form @submit.prevent="handleAuth" class="space-y-4">
                <!-- First Name -->
                <div class="form-group relative">
                    <input
                        type="text"
                        v-model="form.first_name"
                        placeholder="First Name"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.first_name }"
                    />
                    <ErrorMessage :errors="errors" field="first_name" />
                </div>

                <!-- Last Name -->
                <div class="form-group relative">
                    <input
                        type="text"
                        v-model="form.last_name"
                        placeholder="Last Name"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.last_name }"
                    />
                    <ErrorMessage :errors="errors" field="last_name" />
                </div>

                <!-- Email -->
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

                <!-- Username -->
                <div class="form-group relative">
                    <input
                        type="text"
                        v-model="form.username"
                        placeholder="Username"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.username }"
                    />
                    <ErrorMessage :errors="errors" field="username" />
                </div>

                <!-- Password -->
                <div class="form-group relative">
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password"
                        placeholder="Password"
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

                <!-- Confirm Password -->
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

                <!-- Register Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 transition-colors"
                    :disabled="isLoading"
                >
                    {{ isLoading ? 'Please wait...' : 'Register' }}
                </button>
            </form>

            <!-- Login Link -->
            <p class="mt-4 text-center text-gray-600">
                Already have an account?
                <router-link to="/admin/login" class="text-blue-600 hover:text-blue-800">
                    Login
                </router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
    import { reactive, watch, ref } from "vue";
    import { useFormValidation } from "@/composables/useFormValidation";
    import ErrorMessage from "@/views/components/ErrorMessage.vue";
    import { useAuthApi } from '@/composables/useAuthApi';

    const initialFormState  = reactive({
        first_name: "",
        last_name: "",
        email: "",
        username: "",
        password: "",
        password_confirmation: "",
    });

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);
    const { authenticate } = useAuthApi();
    const { handleAuth, isLoading, error } = authenticate('/register', form, clearErrors, 'You have successfully registered.');
    const showPassword = ref(false);

    const toggleShowPassword = () => {
        showPassword.value = !showPassword.value;
    };
    watch(error, (newError) => {
        if (newError) {
            handleApiError(newError);
        }
    });
</script>
