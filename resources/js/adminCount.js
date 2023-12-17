import { ref } from "vue";
const adminCount = ref(0);

const getAdminCount = () => {
    axios.get('/api/admins')
        .then(res => adminCount.value = res.data.adminCount)
        .catch(error => console.log(error));
}

export { adminCount, getAdminCount };