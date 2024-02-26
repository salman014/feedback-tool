import axios from 'axios';
const axiosConfig = {
    headers: {
        "Access-Control-Allow-Origin": "*",
        'Content-Type': 'application/json;',
        'Authorization': `Bearer ${localStorage.getItem('access_token')}`
    },
};
export const logout = async () => {
    try {
        await axios.post('http://127.0.0.1:8000/api/logout', null, axiosConfig);
        localStorage.removeItem('access_token');
    } catch (error) {
        console.error(error.response); // Handle errors if needed
    }
};