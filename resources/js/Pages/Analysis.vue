<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import axios from 'axios';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const imagePreview = ref(null);
const selectedFile = ref(null);
const loading = ref(false);
const result = ref(null);
const selectedModel = ref('resnet');
const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

// Data untuk Chart
const chartData = ref({
    labels: [],
    datasets: [{ data: [], backgroundColor: '#10b994' }]
});

const onFileChange = (e) => {
    if (loading.value) return;

    const file = e.target.files[0];
    if (!file) return;

    if (file.size > MAX_FILE_SIZE) {
        alert('File terlalu besar. Maksimum 5MB.');
        // reset the file input
        e.target.value = '';
        selectedFile.value = null;
        imagePreview.value = null;
        return;
    }

    selectedFile.value = file;
    imagePreview.value = URL.createObjectURL(file);
};

const uploadImage = async () => {
    if (loading.value || !selectedFile.value) return;

    loading.value = true;
    const formData = new FormData();
    formData.append('image', selectedFile.value);
    formData.append('model', selectedModel.value);

    try {
        const response = await axios.post(route('leaf.check'), formData);
        result.value = response.data;

        // Update Chart Data dari API Python
        const labels = Object.keys(response.data.all_predictions);
        const values = Object.values(response.data.all_predictions).map(v => v * 100);

        chartData.value = {
            labels: labels,
            datasets: [{
                label: 'Confidence (%)',
                data: values,
                backgroundColor: '#10b981'
            }]
        };
    } catch (error) {
        alert("Gagal melakukan analisis");
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div v-if="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm px-4">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl text-center">
                <div class="mx-auto mb-4 h-12 w-12 animate-spin rounded-full border-4 border-emerald-200 border-t-emerald-600"></div>
                <h3 class="text-lg font-semibold text-gray-900">Sedang menganalisis gambar</h3>
                <p class="mt-2 text-sm text-gray-600">Mohon tunggu, proses deteksi sedang berjalan</p>
            </div>
        </div>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Leaf Detection Using Deep Learning</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Kiri: Upload Area -->
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Upload Gambar Daun</h3>
                        <div class="flex items-center justify-center w-full">
                            <label :class="['flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50', loading ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-gray-100']">
                                <div v-if="!imagePreview" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/></svg>
                                    <p class="mb-2 text-sm text-gray-500 font-semibold">Klik untuk upload</p>
                                </div>
                                <img v-else :src="imagePreview" class="h-full w-full object-contain p-2 rounded-lg" />
                                <input type="file" class="hidden" @change="onFileChange" accept="image/*" :disabled="loading" />
                            </label>
                        </div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"> <a class="text-red-500">*  </a>Ukuran maksimum file: 5MB.</label>
                        <div class="mb-4">
                            <label class="block text-lg font-medium text-gray-700 mb-2">Pilih Model Arsitektur:</label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" v-model="selectedModel" value="resnet" class="mr-2" :disabled="loading"> ResNet50
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" v-model="selectedModel" value="mobilenet" class="mr-2" :disabled="loading"> MobileNetV3
                                </label>
                                <!-- <label class="flex items-center">
                                    <input type="radio" v-model="selectedModel" value="efficientnet" class="mr-2" :disabled="loading"> EfficientNetB0
                                </label> -->
                            </div>
                        </div>
                        <button @click="uploadImage" :disabled="loading" class="mt-4 w-full bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700 transition disabled:cursor-not-allowed disabled:opacity-70">
                            {{ loading ? 'Menganalisis...' : 'Mulai Deteksi' }}
                        </button>
                    </div>

                    <!-- Kanan: Result Area -->
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Hasil Analisis</h3>

                        <div v-if="result" class="space-y-4">
                            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded">
                                <p class="text-sm text-emerald-700 uppercase font-bold">Hasil Deteksi:</p>
                                <p class="text-2xl font-black text-gray-800">{{ result.detection }}</p>
                                <p class="text-sm text-gray-600 italic">Confidence: {{ result.confidence }}</p>
                            </div>

                            <div class="h-64">
                                <Bar :data="chartData" :options="{ responsive: true, maintainAspectRatio: false }" />
                            </div>
                        </div>

                        <div v-else class="flex items-center justify-center h-full text-gray-400 italic">
                            Belum ada data untuk ditampilkan.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
