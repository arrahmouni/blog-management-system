import axios from "axios";

export const fetchPostDetails = async (slug) => {
    try {
        const response = await axios.get(`/post-details/${slug}`);
        return response.data.data.data;
    } catch (error) {
        console.error("Error fetching post details:", error);
        throw error;
    }
};

export const fetchPostComments = async (postId, page = 1) => {
    try {
        const response = await axios.get(
            `/post-comments/${postId}?page=${page}`
        );

        if (!response.data.data || !response.data.data.data) {
            throw new Error("Invalid comments data structure");
        }

        return {
            data: response.data.data.data,
            paginate: response.data.data.paginate,
        };
    } catch (error) {
        console.error(
            "API Error - fetchPostComments:",
            error.response?.data || error.message
        );
        throw new Error("Failed to load comments");
    }
};

export const addComment = async (postId, content) => {
    try {
        const response = await axios.post(`/post/${postId}/comment`, {
            body: content,
        });

        if (!response.data.data) {
            throw new Error("Invalid comment data structure");
        }

        return response.data;
    } catch (error) {
        console.error(
            "API Error - addComment:",
            error.response?.data || error.message
        );
        throw new Error("Failed to add comment");
    }
};
