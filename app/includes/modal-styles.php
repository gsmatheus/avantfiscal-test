<style>
    .modal-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 8px;
        box-sizing: border-box;
    }

    .modal-content {
        max-height: calc(100vh - 16px);
        overflow-y: auto;
        width: 100%;
        margin: auto;
        box-sizing: border-box;
        position: relative;
    }

    @media (min-width: 640px) {
        .modal-container {
            padding: 16px;
        }

        .modal-content {
            max-height: calc(100vh - 32px);
        }
    }

    @media (max-height: 600px) {
        .modal-content {
            max-height: calc(100vh - 8px);
        }

        .modal-container {
            padding: 4px;
        }
    }

    @media (max-height: 500px) {
        .modal-content {
            max-height: calc(100vh - 4px);
        }

        .modal-container {
            padding: 2px;
        }
    }

    @media (max-width: 480px) {
        .modal-content {
            width: calc(100vw - 8px);
            max-width: calc(100vw - 8px);
        }
    }

    @media (max-width: 768px) and (max-height: 720px) {
        .modal-content {
            max-height: calc(100vh - 12px);
        }

        .modal-container {
            padding: 6px;
        }
    }
</style> 