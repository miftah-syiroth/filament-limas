import { Html5Qrcode, Html5QrcodeSupportedFormats } from 'html5-qrcode'

export default function barcodeScanner({ serialNumberLength }) {
    return {
        scannerOpen: false,
        scanner: null,
        error: null,
        isStarting: false,

        async startCamera() {
            this.error = null
            this.scannerOpen = true
            this.isStarting = true

            await this.$nextTick()

            try {
                this.scanner = new Html5Qrcode('barcode-scanner-reader')

                await this.scanner.start(
                    { facingMode: 'environment' },
                    {
                        fps: 10,
                        aspectRatio: 1,
                        qrbox: (viewfinderWidth, viewfinderHeight) => {
                            const size = Math.min(viewfinderWidth, viewfinderHeight)

                            return {
                                width: Math.floor(size * 0.85),
                                height: Math.floor(size * 0.3),
                            }
                        },
                        formatsToSupport: [
                            Html5QrcodeSupportedFormats.CODE_128,
                            Html5QrcodeSupportedFormats.CODE_39,
                            Html5QrcodeSupportedFormats.QR_CODE,
                        ],
                    },
                    (decodedText) => this.handleScan(decodedText),
                    () => {},
                )
            } catch (exception) {
                this.error = exception?.message ?? 'Unable to access the camera.'
                await this.stopCamera()
            } finally {
                this.isStarting = false
            }
        },

        async handleScan(decodedText) {
            const serialNumber = decodedText.trim().substring(0, serialNumberLength)

            if (serialNumber.length === 0) {
                return
            }

            await this.stopCamera()

            await this.$wire.applyScannedSerialNumber(serialNumber)
        },

        async stopCamera() {
            if (this.scanner) {
                try {
                    if (this.scanner.isScanning) {
                        await this.scanner.stop()
                    }

                    await this.scanner.clear()
                } catch (exception) {
                    // Ignore cleanup errors when the scanner was not fully started.
                }

                this.scanner = null
            }

            this.scannerOpen = false
            this.isStarting = false
        },
    }
}
