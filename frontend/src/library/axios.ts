import axios from 'axios';
const axiosIns = axios.create({
    baseURL:'http://localhost:8000/ims/backend/api/'
});

axiosIns.interceptors.request.use(config => { 
    const bearerToken = localStorage.getItem("token"); 
    config.headers.Authorization = `Bearer ${bearerToken}`;
    return config;
}, error => {
    return Promise.reject(error);
}); 

export default axiosIns;