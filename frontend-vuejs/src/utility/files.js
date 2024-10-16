/* eslint-disable no-param-reassign */
export function bytesToSize(bytes) {
    if (bytes === 0) { return '0.00 B'; }
    const e = Math.floor(Math.log(bytes) / Math.log(1024));
    return `${(bytes / 1024 ** e).toFixed(1)} ${' KMGTP'.charAt(e)}B`;
}

export function generatePreviewUrl(file) {
    return new Promise((resolve) => {
        if (file.type.includes('image') && file.type !== 'image/tiff') {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                resolve(reader.result);
            };
            reader.onerror = () => {
                resolve(null);
            };
        } else {
            resolve(null);
        }
    });
}

export function generateUploadingFile(file) {
    return new Promise((resolve) => {
        generatePreviewUrl(file)
            .then((result) => {
                resolve({
                    file,
                    extension: file.name.split('.').pop(),
                    name: file.name,
                    previewUrl: result,
                    size: bytesToSize(file.size),
                    sizeUploaded: '0',
                    progress: 0,
                    isUploaded: false,
                    uploading: true,
                    startedTime: Date.now(),
                    timeRemaining: 0,
                    errors: [],
                });
            });
    });
}

export function updateUploadingFileInformations(fileUploading, progressEvent) {
    const timeTaken = Date.now() - fileUploading.startedTime;
    const uploadSpeed = progressEvent.loaded / timeTaken;
    fileUploading.sizeUploaded = bytesToSize(progressEvent.loaded);
    fileUploading.size = bytesToSize(progressEvent.total);
    fileUploading.progress = (progressEvent.loaded * 100) / progressEvent.total;
    fileUploading.timeRemaining = ((progressEvent.total - progressEvent.loaded) / uploadSpeed) / 1000;
}

export function fileIcon(fileType) {
    const filesTypes = {
        bmp: 'fa-file-image-o',
        csv: 'fa-file-excel-o',
        gif: 'fa-file-image-o',
        jpeg: 'fa-file-image-o',
        jpg: 'fa-file-image-o',
        pdf: 'fa-file-pdf-o',
        png: 'fa-file-image-o',
        rar: 'fa-file-archive-o',
        tif: 'fa-file-image-o',
        tiff: 'fa-file-image-o',
        txt: 'fa-file-text-o',
        xls: 'fa-file-excel-o',
        xlsm: 'fa-file-excel-o',
        xlsx: 'fa-file-excel-o',
        zip: 'fa-file-archive-o',
    };
    return filesTypes[fileType] === undefined ? 'fa-file' : filesTypes[fileType];
}

export default {
    bytesToSize,
    generatePreviewUrl,
    fileIcon,
};
