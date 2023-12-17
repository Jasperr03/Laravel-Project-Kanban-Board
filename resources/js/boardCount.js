import { ref } from "vue";
const boardCount = ref(0);

const getBoardCount = () => {
    axios.get('/api/boards')
        .then(res => boardCount.value = res.data.boardCount)
        .catch(error => console.log(error));
}
export { boardCount, getBoardCount };