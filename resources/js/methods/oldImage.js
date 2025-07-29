function imageUploader(oldImageUrl = '') {
    return {
        previewUrl: null,
        oldImage: oldImageUrl,
        removeOldImage: false,

        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewUrl = URL.createObjectURL(file);
                this.removeOldImage = false;
            }
        },

        removeImage() {
            this.previewUrl = null;
            this.oldImage = null;
            this.removeOldImage = true;
        }
    };
}
