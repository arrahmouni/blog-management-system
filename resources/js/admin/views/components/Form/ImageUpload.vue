<template>
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <div
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop"
            :class="[
                dragClasses,
                'relative mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-lg transition-colors',
            ]"
        >
            <div class="space-y-1 text-center">
                <!-- Preview Section -->
                <div v-if="previewUrl" class="relative">
                    <img
                        :src="previewUrl"
                        :alt="previewAlt"
                        class="mx-auto max-h-48 rounded-lg object-cover"
                    />
                    <button
                        type="button"
                        @click="removeImage"
                        class="absolute -top-3 -right-3 p-1 bg-red-500 rounded-full hover:bg-red-600 transition-colors"
                    >
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Upload Content -->
                <div v-else>
                    <slot name="icon">
                        <svg
                            class="mx-auto h-12 w-12 text-gray-400"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 48 48"
                        >
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </slot>

                    <div class="flex text-sm text-gray-600">
                        <label
                            :for="inputId"
                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500"
                        >
                            <span>{{ uploadText }}</span>
                            <input
                                :id="inputId"
                                type="file"
                                class="sr-only"
                                :accept="acceptedExtensions"
                                @change="handleFileSelect"
                            />
                        </label>
                        <p class="pl-1">{{ dragText }}</p>
                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        {{ allowedFilesText }} up to {{ formattedMaxSize }}
                    </p>
                </div>

                <!-- Status Messages -->
                <div v-if="statusMessage" class="text-sm mt-2">
                    <p :class="statusClasses">
                        {{ statusMessage?.message }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed, toRefs } from "vue";
    import { v4 as uuidv4 } from "uuid";

    const props = defineProps({
        modelValue: {
            type: [File, String, null],
            default: null,
        },
        fieldName: {
            type: String,
            default: "image",
        },
        label: {
            type: String,
            default: "Upload Image",
        },
        required: {
            type: Boolean,
            default: false,
        },
        acceptedExtensions: {
            type: String,
            default: "image/*",
        },
        maxSize: {
            type: Number,
            default: 5, // In MB
        },
        previewAlt: {
            type: String,
            default: "Preview",
        },
        existingImage: {
            type: String,
            default: null,
        },
        uploadText: {
            type: String,
            default: "Upload a file",
        },
        dragText: {
            type: String,
            default: "or drag and drop",
        },
        allowedFilesText: {
            type: String,
            default: "PNG, JPG, GIF",
        },
    });

    const emit = defineEmits([
        "update:modelValue",
        "file-selected",
        "file-removed",
    ]);

    const { existingImage } = toRefs(props);
    const isDragging = ref(false);
    const previewUrl = ref(existingImage.value || null);
    const statusMessage = ref(null);
    const inputId = ref(uuidv4());

    const dragClasses = computed(() => {
        return isDragging.value ? "border-blue-500 bg-blue-50" : "border-gray-300";
    });

    const statusClasses = computed(() => {
        return {
            "text-green-500 font-medium": statusMessage.value?.type === "success",
            "text-red-500 font-medium": statusMessage.value?.type === "error",
        };
    });

    const formattedMaxSize = computed(() => {
        return `${props.maxSize}MB`;
    });

    const handleFileSelect = (event) => {
        const file = event.target.files[0];
        handleFile(file);
    };

    const handleDrop = (event) => {
        isDragging.value = false;
        const file = event.dataTransfer.files[0];
        handleFile(file);
    };

    const handleFile = (file) => {
        if (!file) return;

        // Validate file type
        if (!isValidFileType(file)) {
            setStatus(
                "error",
                `Invalid file type. Allowed: ${props.allowedFilesText}`
            );
            return;
        }

        // Validate file size
        if (file.size > props.maxSize * 1024 * 1024) {
            setStatus("error", `File too large (max ${formattedMaxSize.value})`);
            return;
        }

        // Read file for preview
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target.result;
            setStatus("success", "File selected successfully");
            emitFile(file);
        };
        reader.readAsDataURL(file);
    };

    const isValidFileType = (file) => {
        if (props.acceptedExtensions === "image/*") {
            return file.type.startsWith("image/");
        }
        return props.acceptedExtensions
            .split(",")
            .map((ext) => ext.trim())
            .includes(file.type);
    };

    const emitFile = (file) => {
        emit("update:modelValue", file);
        emit("file-selected", {
            file,
            fieldName: props.fieldName,
        });
    };

    const removeImage = () => {
        previewUrl.value = null;
        emit("update:modelValue", null);
        emit("file-removed", props.fieldName);
        setStatus(null, null);
    };

    const setStatus = (type, message) => {
        statusMessage.value = type ? { type, message } : null;
    };
</script>
