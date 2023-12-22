<script>
import { ref, onMounted } from 'vue';
import { Bar } from 'vue-chartjs';
import Chart from 'chart.js/auto'; // Import Chart.js library
import dayjs from 'dayjs'; // Import dayjs
import html2pdf from 'html2pdf.js';


export default {
    extends: Bar, // Switch to Bar chart type
    data() {
        return {
            chartData: {
                labels: [],
                datasets: [
                    {
                        label: 'User Registrations',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        data: [],
                    },
                ],
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
            chartInstance: null,
        };
    },
    methods: {
        async fetchUserRegistrations() {
            try {
                const response = await axios.get('/api/getUsers');
                const data = response.data;

                // Group data by month
                const groupedData = data.users.reduce((acc, entry) => {
                    const month = dayjs(entry.created_at).format('MMMM YYYY');
                    acc[month] = (acc[month] || 0) + 1;
                    return acc;
                }, {});

                // Sort labels in ascending order (from past to present)
                const labels = Object.keys(groupedData).sort((a, b) => {
                    const dateA = new Date(a);
                    const dateB = new Date(b);
                    return dateA - dateB;
                });
 
                const counts = Object.values(groupedData);

                this.chartData.labels = labels;
                this.chartData.datasets[0].data = counts;

                if (this.chartInstance) {
                    // Update chart data and options
                    this.chartInstance.data.labels = this.chartData.labels;
                    this.chartInstance.data.datasets[0].data = this.chartData.datasets[0].data;
                    this.chartInstance.update();
                }

                const ctx = this.$refs.chart;
                this.chartInstance = new Chart(ctx, {
                    type: 'bar', // Switch to Bar chart type
                    data: this.chartData,
                    options: this.chartOptions,
                });

            } catch (error) {
                console.error('Error fetching user registrations', error);
            }
        },

        async downloadChart(format) {
            try {
                const canvas = this.$refs.chart;
 

                const timestamp = new Date().toISOString().replace(/[-T:\.Z]/g, '');
                const filename = `user_graph_${timestamp}`;

                if (format === 'png') {
                    const url = canvas.toDataURL('image/png');
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `${filename}.png`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                } else if (format === 'print') {
 

                    // Create a new canvas element
                    const newCanvas = document.createElement('canvas');
                    const newCtx = newCanvas.getContext('2d');

                    // Set the new canvas size
                    newCanvas.width = canvas.width;
                    newCanvas.height = canvas.height;

                    // Draw the content of the existing chart canvas onto the new canvas
                    newCtx.drawImage(canvas, 0, 0);

                    // Open a new window with the new canvas content
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write(`<html><head><title>${filename}</title></head><body>`);
                    printWindow.document.write('<img src="' + newCanvas.toDataURL() + '" />');
                    printWindow.document.write('</body></html>');

                    // Trigger the print dialog
                    printWindow.print();
                    printWindow.document.close();

                }
            } catch (error) {
                console.error('Error downloading chart', error);
            }
        },
    },
    mounted() {
        this.fetchUserRegistrations();
    },
};
</script>

<template>
    <div class="userReport"> 

        <div class="flex items-center justify-between mb-4">
            <h2 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">User Registration Graph</h2>
        
            <div class="downloads flex items-center gap-2">

                <button @click="downloadChart('png')" class="px-2 py-1 bg-blue-500 text-white rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"> <path d="M0 96C0 60.7 28.7 32 64 32H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zM323.8 202.5c-4.5-6.6-11.9-10.5-19.8-10.5s-15.4 3.9-19.8 10.5l-87 127.6L170.7 297c-4.6-5.7-11.5-9-18.7-9s-14.2 3.3-18.7 9l-64 80c-5.8 7.2-6.9 17.1-2.9 25.4s12.4 13.6 21.6 13.6h96 32H424c8.9 0 17.1-4.9 21.2-12.8s3.6-17.4-1.4-24.7l-120-176zM112 192a48 48 0 1 0 0-96 48 48 0 1 0 0 96z" fill="#fff"/></svg>
                    </button>
        
                    <button @click="downloadChart('print')" class="px-2 py-1 bg-green-500 text-white rounded-md"> 
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"> <path d="M128 0C92.7 0 64 28.7 64 64v96h64V64H354.7L384 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zM384 352v32 64H128V384 368 352H384zm64 32h32c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32H64v64c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V384zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" fill="#fff"/></svg>
                    </button>
            </div>
        </div>

        <div style="height: 300px;"> <!-- Set a fixed height for the container -->
            <canvas ref="chart"></canvas>
        </div>
 
         
    </div>
</template>

<style scoped>
/* Add style for the canvas text color */
#chart {
    color: white;
    
}

.userReport{
    position: relative;
    z-index: 90;
}
</style>
