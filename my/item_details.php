
<style>
    /* ---------- Modal ---------- */

    /* General modal styles */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
        justify-content: center; /* Center the modal content horizontally */
        align-items: center; /* Center the modal content vertically */
        font-family: "Inter", sans-serif;
    }

    .modal__content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: fit-content;
        max-width: 80%; /* Optional: Limit modal width */
        height: fit-content;
        max-height: 80%;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: relative; /* Required for close button positioning */
        overflow: auto;
    }

    /* Close button styles */
    .modal__close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        position: absolute;
        top: 10px;
        right: 15px;
        cursor: pointer;
    }

    .modal__close:hover,
    .modal__close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    /* Item Details Modal Specific Styles */
    .modal--item-details .modal__title {
        font-size: 24px;
        margin-bottom: 15px;
    }

    .modal__table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    .modal__table th, .modal__table td {
        text-align: left;
        padding: 8px;
        border-bottom: 1px solid #ddd;
    }

    .modal__table th {
        background-color: #f2f2f2;
    }

    .modal__content--general,
    .modal__content--customs {
        display: flex;
        flex-direction: column;
    }

    .modal__content--customs {
        margin-top: 20px;
    }
</style>
<!-- Modal for Item Details -->
<div class="modal   modal--item-details" id="itemDetailsModal">
    <div class="modal__content">
        <span class="modal__close" id="closeItemDetails">&times;</span>
        <div class="modal__content--general">
            <h2 class="modal__title">Item Details</h2>
            <table class="modal__table">
                <tr>
                    <th>Furniture:</th>
                    <td id="modalFurniture"></td>
                </tr>
                <tr>
                    <th>Description:</th>
                    <td id="modalDescription"></td>
                </tr>
                <tr>
                    <th>Quantity:</th>
                    <td id="modalQuantity"></td>
                </tr>
                <tr>
                    <th>Price:</th>
                    <td id="modalPrice"></td>
                </tr>
                <tr>
                    <th>Reference Image:</th>
                    <td id="modalRefImage"></td>
                </tr>
            </table>
        </div>
        <div class="modal__content--customs" id="customsDetails">
            <h2 class="modal__title">Item Customization</h2>
            <table class="modal__table">
                <tr>
                    <th>Dimensions:</th>
                    <td id="modalDimensions"></td>
                </tr>
                <tr>
                    <th>Materials:</th>
                    <td id="modalMaterials"></td>
                </tr>
                <tr>
                    <th>Fabric:</th>
                    <td id="modalFabric"></td>
                </tr>
                <tr>
                    <th>Color:</th>
                    <td id="modalColor"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script src="/js/my/item_details.js"></script>