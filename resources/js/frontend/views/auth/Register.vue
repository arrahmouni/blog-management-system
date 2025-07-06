<template>
    <div
        class="font-poppins bg-gradient-to-br from-indigo-50 to-purple-50 min-h-screen flex items-center justify-center p-4"
    >
        <div
            class="max-w-6xl w-full bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row"
        >
            <!-- Left Side -->
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
                        Create an Account
                    </h1>
                    <p class="text-indigo-100 mb-10">
                        Join us and start your journey with the community
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

            <!-- Right Side - Form -->
            <div class="md:w-1/2 p-8 md:p-12 lg:p-16">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    Sign Up
                </h2>
                <p class="text-gray-600 mb-8">
                    Fill in the fields to create your account
                </p>

                <form @submit.prevent="handleAuth" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <input
                                v-model="form.first_name"
                                type="text"
                                placeholder="First Name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                            />
                            <ErrorMessage :errors="errors" field="first_name" />
                        </div>

                        <!-- Last Name -->
                        <div>
                            <input
                                v-model="form.last_name"
                                type="text"
                                placeholder="Last Name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                            />
                            <ErrorMessage :errors="errors" field="last_name" />
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="Email Address"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                        />
                        <ErrorMessage :errors="errors" field="email" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <input
                            v-model="form.phone"
                            type="text"
                            placeholder="Phone Number"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                        />
                        <ErrorMessage :errors="errors" field="phone" />
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            placeholder="Password"
                            class="w-full px-4 pl-11 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
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
                    <div>
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password_confirmation"
                            placeholder="Confirm Password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                        />
                        <ErrorMessage
                            :errors="errors"
                            field="password_confirmation"
                        />
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="w-full bg-primary text-white font-medium py-3 rounded-xl shadow-lg hover:bg-primary-dark transition disabled:opacity-50"
                    >
                        <span v-if="!isLoading">Create Account</span>
                        <span v-else
                            ><i class="fas fa-spinner fa-spin mr-2"></i>
                            Processing...</span
                        >
                    </button>

                    <p class="text-center text-gray-600 mt-8">
                        Already have an account?
                        <router-link
                            to="/login"
                            class="text-primary font-medium hover:underline"
                            >Sign In</router-link
                        >
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, reactive, watch } from "vue";
    import { useRouter } from "vue-router";
    import { useFormValidation } from "../../../admin/composables/useFormValidation";
    import { useAuthApi } from "../../../admin/composables/useAuthApi";
    import ErrorMessage from "../../../admin/views/components/ErrorMessage.vue";
    import { useUser } from "../../../admin/composables/useUser";

    // Initial form state
    const initialFormState = reactive({
        first_name: "",
        last_name: "",
        email: "",
        phone: "",
        password: "",
        password_confirmation: "",
    });

    const { form, errors, clearErrors, handleApiError } =
        useFormValidation(initialFormState);
    const { authenticate } = useAuthApi();
    const { login } = useUser();
    const router = useRouter();

    const { handleAuth, isLoading, error, response } = authenticate(
        "/register", // 🔁 adjust according to your backend
        form,
        clearErrors,
        "You have successfully registered.",
        "/"
    );

    // handle API errors
    watch(error, (newError) => {
        if (newError) handleApiError(newError);
    });

    // store token & role
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

    // Features for left side
    const features = [
        "Access to thousands of articles",
        "Personalized content recommendations",
        "Save your favorite posts",
        "Join the conversation with comments",
    ];
</script>
