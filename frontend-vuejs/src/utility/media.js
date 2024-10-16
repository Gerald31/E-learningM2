import { configs } from '@/configs';
import { useMessengerStore } from "@/stores";
import pdfIcon from '@/assets/images/ic_pdf.svg';
import movieIcon from '@/assets/images/ic_moovie.svg';
import imageIcon from '@/assets/images/ic_image.svg';
import documentIcon from '@/assets/images/ic_document.svg';

export const FOLDER_PARENT = "storage/";

export const imageExtensions = ['jpg' , 'jpeg', 'png', 'bmp', 'gif'];

export const  videoExtensions = ["3g2","3gp","mkv","mov","mp4","mpeg","mpg","vob","webm"];

export const  documentExtensions = ['ppt', 'pptx', 'odt', 'xls', 'xlsx', 'doc', 'docx'];

export const  pdfExtension =['pdf'];
export const fullPathPicture = (pathFile) => {
    if (pathFile) {
        return configs.BASE_URL + FOLDER_PARENT + pathFile;
    }
    return pathFile;
}

export const setIconType = (pathFile = null) => {
    if (pathFile !== null) {
        const extension = getFileExtension(pathFile);
        if (extension.includes(pdfExtension)) {
            return pdfIcon;
        } else if (extension.includes(videoExtensions)) {
            return movieIcon;
        } else if (extension.includes(imageExtensions)) {
            return imageIcon;
        } else {
            return documentIcon;
        }
    }
}

export const getFileExtension = (pathFile) => {
    return pathFile?.split('.').pop().toLowerCase();
}
