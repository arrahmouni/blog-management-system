import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";

export const useAuthApi = () => {
    const router = useRouter();
    const toast = useToast();
    const isLoading = ref(false);
    const error = ref(null);
    const message = ref("");
    const messageClass = ref("");


    // Function for handling authentication (login/register)
    const authenticate = (apiRoute, formData, clearErrors, successMessage, target) => {
        const response = ref(null)
        const handleAuth = async () => {
            try {
                clearErrors();
                isLoading.value = true;
                error.value = null;

                const res = await axios.post(apiRoute, formData);
                response.value = res;

                toast.success(successMessage);

                localStorage.setItem("authToken",res.data.data.token.access_token);
                localStorage.setItem("userRole", res.data.data.user.role);

                axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${res.data.data.token.access_token}`;

                router.push(target);

            } catch (err) {
                error.value = err;
                throw err;
            } finally {
                isLoading.value = false;
            }
        };

        return {
            handleAuth,
            isLoading,
            error,
            response
        };
    };

    const handleForgotPassword = async (email) => {
        try {
            isLoading.value = true;
            error.value = null;
            message.value = "";

            const response = await axios.post("/forgot-password", {
                email: email,
            });

            message.value        = "Password reset link sent to your email!";
            messageClass.value   = "bg-green-100 text-green-800";
            toast.success("Password reset link sent to your email!");

            router.push("/admin/login");
        } catch (err) {
            error.value = err;
            message.value =
                err.response?.data?.message ||
                "An error occurred while sending the reset link";
            messageClass.value = "bg-red-100 text-red-800";
            throw err;
        } finally {
            isLoading.value = false;
        }
    };

    const handleResetPassword = async (email, token, password, password_confirmation) => {
        try {
            isLoading.value = true;
            error.value = null;
            message.value = "";

            const response = await axios.post("/reset-password", {
                email,
                token,
                password,
                password_confirmation
            });

            message.value = "Password reset successfully!";
            messageClass.value = "bg-green-100 text-green-800";
            toast.success("Password reset successfully!");

            router.push("/admin/login");
        } catch (err) {
            error.value = err;
            message.value =
                err.response?.data?.message ||
                "An error occurred while resetting your password";
            messageClass.value = "bg-red-100 text-red-800";
            throw err;
        } finally {
            isLoading.value = false;
        }
    };

    return {
        authenticate,
        handleForgotPassword,
        handleResetPassword,
        isLoading,
        error,
        message,
        messageClass,
    };
};
