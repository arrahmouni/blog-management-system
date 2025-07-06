<template>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center relative"
    style="background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1172&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <!-- Content -->
        <div class="max-w-lg w-full mx-auto p-8 bg-white bg-opacity-90 rounded-lg shadow-md relative z-10">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Welcome Back!
                </h1>
                <p class="text-gray-600">
                    Please log in to continue
                </p>
            </div>

            <form @submit.prevent="handleAuth" class="space-y-4">
                <!-- Email Input -->
                <div class="form-group relative">
                    <input
                        type="login"
                        v-model="form.login"
                        placeholder="Email or Phone Number"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.login }"
                    />
                    <ErrorMessage :errors="errors" field="login" />
                </div>

                <!-- Password Input with Show/Hide Icon -->
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

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 transition-colors"
                    :disabled="isLoading"
                >
                    {{ isLoading ? 'Please wait...' : 'Login' }}
                </button>
            </form>

            <!-- Register Link -->
            <!-- <p class="mt-4 text-center text-gray-600">
                Don't have an account?
                <router-link to="/admin/register" class="text-blue-600 hover:text-blue-800">
                    Register
                </router-link>
            </p> -->

            <!-- Forgot Password Link -->
            <p class="mt-4 text-center text-gray-600">
                <router-link to="/admin/forgot-password" class="text-blue-600 hover:text-blue-800">
                    Forgot Password?
                </router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
    import { reactive, watch, ref  } from "vue";
    import { useFormValidation } from "../../composables/useFormValidation";
    import { useAuthApi } from '../../composables/useAuthApi';
    import ErrorMessage from "../../views/components/ErrorMessage.vue";

    const initialFormState  = reactive({
        login: "",
        password: "",
    });

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);
    const { authenticate } = useAuthApi();
    const { handleAuth, isLoading, error } = authenticate('/admin/login', form, clearErrors, 'You have successfully logged in.', '/admin/dashboard');
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
