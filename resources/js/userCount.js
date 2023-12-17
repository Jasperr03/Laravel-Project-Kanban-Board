import { ref } from "vue";
const userCount = ref(0);

const getUserCount = () => {
    axios.get('/api/users')
        .then(res => userCount.value = res.data.userCount)
        .catch(error => console.log(error));
}

export { userCount, getUserCount };