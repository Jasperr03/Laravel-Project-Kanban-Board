import { ref } from "vue";
const taskCount = ref(0);

const getTaskCount = () => {
    axios.get('/api/cards')
        .then(res => taskCount.value = res.data.taskCount)
        .catch(error => console.log(error));
}

export { taskCount, getTaskCount };