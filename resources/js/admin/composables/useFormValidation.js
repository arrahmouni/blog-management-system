import { reactive } from "vue";
import { useToast } from "vue-toastification";

export function useFormValidation(initialFormState) {
    const toast = useToast();

    // Reactive form state
    const form = reactive({ ...initialFormState });

    // Reactive errors state
    const errors = reactive(
        Object.keys(initialFormState).reduce((acc, key) => {
            acc[key] = [];
            return acc;
        }, {})
    );

    // Function to clear all errors
    const clearErrors = () => {
        Object.keys(errors).forEach((key) => (errors[key] = []));
    };

    // Function to handle validation errors
    const handleValidationErrors = (validationErrors) => {
        Object.keys(validationErrors).forEach((key) => {
            if (errors[key] !== undefined) {
                errors[key] = validationErrors[key];
            }
        });
    };

    // Function to handle API errors
    const handleApiError = (error) => {
        if (error.response) {
            if(error.response.status === 422) {
                handleValidationErrors(error.response.data.errors);
                toast.error("Please check the data entered and try again.")
            }
            else
                toast.error(error.response.data.message);
        } else {
            toast.error("An error occurred. Please try again.");
        }
    };

    return {
        form,
        errors,
        clearErrors,
        handleApiError,
    };
}
