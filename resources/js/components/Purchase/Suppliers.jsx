import React, { useState, useEffect } from "react";
import axios from "axios";
import Select from "react-select";

const Suppliers = ({ setSupplierId,oldSupplier }) => {
    const [suppliers, setSuppliers] = useState([]);
    const [selectedSupplier, setSelectedSupplier] = useState(null);
    const [newSupplier, setNewSupplier] = useState({
        name: "",
        phone: "",
        address: "",
    });
    const [errors, setErrors] = useState({});
    useEffect(() => {
        axios.get("/admin/suppliers").then((response) => {
            const supplierOptions = response?.data?.map((supplier) => ({
                value: supplier.id,
                label: supplier.name,
            }));
            setSuppliers(supplierOptions);
        });
    }, []);

    useEffect(() => {
        setSupplierId(selectedSupplier?.value);
    }, [selectedSupplier]);
    
    useEffect(() => {
        setSelectedSupplier(oldSupplier);
    }, [oldSupplier]);
    // Show the modal using Bootstrap's JavaScript
    const handleShow = () => {
        const modal = new window.bootstrap.Modal(
            document.getElementById("createSupplierModal")
        );
        modal.show();
    };

    // Close the modal properly
    const handleClose = () => {
        const modalElement = document.getElementById("createSupplierModal");
        const modal = new window.bootstrap.Modal(modalElement); // Initialize the modal
        modal.hide(); // Call the hide method to close the modal
    };

    // Handle input change inside the modal
    const handleInputChange = (e) => {
        setNewSupplier({ ...newSupplier, [e.target.name]: e.target.value });
    };

    // Handle form submission to create a new supplier
    const handleCreateSupplier = () => {
        axios
            .post("/admin/create/suppliers", newSupplier)
            .then((response) => {
                const newSupplier = response.data;
                const newOption = {
                    value: newSupplier.id,
                    label: newSupplier.name,
                };
                setSuppliers((prev) => [newOption, ...prev]);
                setSelectedSupplier(newOption);
                setNewSupplier({ name: "", phone: "", address: "" });
                handleClose(); // Close modal on success
            })
            .catch((error) => {
                if (error.response && error.response.data.errors) {
                    setErrors(error.response.data.errors);
                } else {
                    console.error("Error creating supplier:", error);
                }
            });
    };

    const handleChange = (newValue) => {
        setSelectedSupplier(newValue);
    };

    return (
        <div>
            <Select
                isClearable
                options={suppliers}
                onChange={handleChange}
                value={selectedSupplier}
                placeholder="เลือกซัพพลายเออร์"
                required
            />
            
            {/* <button
                type="button"
                className="btn btn-primary mt-3"
                onClick={handleShow}
            >
                เพิ่มซัพพลายเออร์
            </button>
            <div
                className="modal fade"
                id="createSupplierModal"
                tabIndex="-1"
                aria-labelledby="createSupplierModalLabel"
                aria-hidden="true"
            >
                <div className="modal-dialog">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5
                                className="modal-title"
                                id="createSupplierModalLabel"
                            >
                                เพิ่มซัพพลายเออร์
                            </h5>
                            <span className="text-bold" onClick={handleClose}>X</span>
                        </div>
                        <div className="modal-body">
                            <div className="mb-3">
                                <label
                                    htmlFor="supplierName"
                                    className="form-label"
                                >
                                    ชื่อซัพพลายเออร์
                                </label>
                                <input
                                    type="text"
                                    className={`form-control ${
                                        errors.name ? "is-invalid" : ""
                                    }`}
                                    id="supplierName"
                                    name="name"
                                    value={newSupplier.name}
                                    onChange={handleInputChange}
                                    placeholder="เช่น ร้านวัตถุดิบสด"
                                />
                                {errors.name && (
                                    <div className="invalid-feedback">
                                        {errors.name}
                                    </div>
                                )}
                            </div>

                            <div className="mb-3">
                                <label
                                    htmlFor="supplierPhone"
                                    className="form-label"
                                >
                                    Phone
                                </label>
                                <input
                                    type="text"
                                    className={`form-control ${
                                        errors.phone ? "is-invalid" : ""
                                    }`}
                                    id="supplierPhone"
                                    name="phone"
                                    value={newSupplier.phone}
                                    onChange={handleInputChange}
                                    placeholder="เช่น 0811111111"
                                />
                                {errors.phone && (
                                    <div className="invalid-feedback">
                                        {errors.phone}
                                    </div>
                                )}
                            </div>

                            <div className="mb-3">
                                <label
                                    htmlFor="supplierAddress"
                                    className="form-label"
                                >
                                    Address
                                </label>
                                <input
                                    type="text"
                                    className="form-control"
                                    id="supplierAddress"
                                    name="address"
                                    value={newSupplier.address}
                                    onChange={handleInputChange}
                                    placeholder="เช่น ตลาดสด"
                                />
                            </div>
                        </div>
                        <div className="modal-footer">
                            <button
                                type="button"
                                className="btn btn-secondary"
                                data-bs-dismiss="modal"
                                onClick={handleClose}
                            >
                                Cancel
                            </button>
                            <button
                                type="button"
                                className="btn btn-primary"
                                onClick={handleCreateSupplier}
                            >
                                บันทึกซัพพลายเออร์
                            </button>
                        </div>
                    </div>
                </div>
            </div> */}
        </div>
    );
};

export default Suppliers;
