import axios from 'axios'
import Swal from 'sweetalert2'
const axiosIns = axios.create({
    timeout: 100000,
})

axiosIns.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token')

axiosIns.interceptors.response.use((response) => {
    return response;
}, (error) => {
    const { status } = error.response
    if (status === 401) {
        localStorage.setItem('token', '')
        localStorage.setItem('role', '')
        location.reload()
    }
    if (status >= 500 && typeof error.response.data === "string") {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.response.data,
            reverseButtons: true,
            confirmButtonText: 'Ok',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33',
        })
    }
    return Promise.reject(error);
});

export default axiosIns
