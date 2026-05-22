import React, {useEffect, useState, useCallback } from "react";
import axios from "axios";
import Swal from "sweetalert2";
import Cart from "./Cart";
import toast, { Toaster } from "react-hot-toast";
import CustomerSelect from "./CutomerSelect";

import SuccessSound from "../sounds/beep-07a.mp3";
import WarningSound from "../sounds/beep-02.mp3";
import playSound from "../utils/playSound";

export default function Pos() {
    const [products, setProducts] = useState([]);
    const [carts, setCarts] = useState([]);
    const [availableRewards, setAvailableRewards] = useState([]);
    const [appliedRewards, setAppliedRewards] = useState([]);
    const [rewardDiscount, setRewardDiscount] = useState(0);
    const [orderDiscount, setOrderDiscount] = useState(0);
    const [paid, setPaid] = useState(0);
    const [due, setDue] = useState(0);
    const [total, setTotal] = useState(0);
    const [updateTotal, setUpdateTotal] = useState(0);
    const [customerId, setCustomerId] = useState();
    const [cartUpdated, setCartUpdated] = useState(false);
    const [productUpdated, setProductUpdated] = useState(false);
    const [orderType, setOrderType] = useState("dine_in");
    const [notes, setNotes] = useState("");
    const [searchQuery, setSearchQuery] = useState("");
    const [searchBarcode, setSearchBarcode] = useState("");
    const { protocol, hostname, port } = window.location;
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(0);
    const [loading, setLoading] = useState(false);
    const fullDomainWithPort = `${protocol}//${hostname}${
        port ? `:${port}` : ""
    }`;
    const getProducts = useCallback(
        async (search = "", page = 1, barcode = "") => {
            setLoading(true);
            try {
                const res = await axios.get('/admin/get/products', {
                    params: { search, page, barcode },
                });
                const productsData = res.data;
                setProducts((prev) => [...prev, ...productsData.data]); // Append new products
                if (productsData.data.length === 1 && barcode != "") {
                    addProductToCart(productsData.data[0].id);
                    getCarts();
                }
                setTotalPages(productsData.meta.last_page); // Get total pages
            } catch (error) {
                console.error("ไม่สามารถโหลดสินค้าได้:", error);
            } finally {
                setLoading(false); // Set loading to false
            }
        },
        []
    );
    const getUpdatedProducts = useCallback(async () => {
        try {
            const res = await axios.get('/admin/get/products');
            const productsData = res.data;
            setProducts(productsData.data);
            setTotalPages(productsData.meta.last_page); // Get total pages
        } catch (error) {
            console.error("ไม่สามารถโหลดสินค้าได้:", error);
        }
    }, []);
    useEffect(() => {
        getUpdatedProducts();
    }, [productUpdated]);

    const getCarts = async () => {
        try {
            const res = await axios.get('/admin/cart');
            const data = res.data;
            setTotal(data?.total);
            setUpdateTotal(data?.total - orderDiscount);
            setCarts(data?.carts);
        } catch (error) {
            console.error("ไม่สามารถโหลดตะกร้าได้:", error);
        }
    };

    useEffect(() => {
        getCarts();
    }, []);

    useEffect(() => {
        getCarts();
    }, [cartUpdated]);

    useEffect(() => {
        if (total > 0 && customerId) {
            axios.get('/admin/get/rewards', { params: { customer_id: customerId, total: total } })
                .then(res => setAvailableRewards(res.data))
                .catch(err => console.error(err));
        } else {
            setAvailableRewards([]);
            setAppliedRewards([]);
            setRewardDiscount(0);
        }
    }, [total, customerId]);

    useEffect(() => {
        let disc = 0;
        appliedRewards.forEach(r => {
            if (r.benefit_type === 'fixed_discount') disc += parseFloat(r.benefit_value);
            else if (r.benefit_type === 'percent_discount') disc += (parseFloat(total) * (parseFloat(r.benefit_value) / 100));
        });
        setRewardDiscount(disc);
    }, [appliedRewards, total]);

    useEffect(() => {
        let paid1 = parseFloat(paid) || 0;
        let manualDisc = parseFloat(orderDiscount) || 0;
        let rewardDisc = parseFloat(rewardDiscount) || 0;

        const updatedTotalAmount = parseFloat(total) - manualDisc - rewardDisc;
        const dueAmount = updatedTotalAmount - paid1;
        setUpdateTotal(updatedTotalAmount?.toFixed(2));
        setDue(dueAmount?.toFixed(2));
    }, [orderDiscount, rewardDiscount, paid, total]);
    useEffect(() => {
        if (searchQuery) {
            setProducts([]);
            getProducts(searchQuery, currentPage, "");
        }
        setSearchBarcode("");
    }, [currentPage, searchQuery]);

    useEffect(() => {
        if (searchBarcode) {
            setProducts([]);
           getProducts("", currentPage, searchBarcode);
        }
    }, [searchBarcode]);

    // Infinite scroll logic
    useEffect(() => {
        const handleScroll = () => {
            if (
                window.innerHeight + document.documentElement.scrollTop >=
                document.documentElement.offsetHeight
            ) {
                // Load next page if not on the last page
                if (currentPage < totalPages) {
                    setCurrentPage((prev) => prev + 1);
                }
            }
        };

        window.addEventListener("scroll", handleScroll);
        return () => {
            window.removeEventListener("scroll", handleScroll);
        };
    }, [currentPage, totalPages]);

    function addProductToCart(id) {
        Swal.fire({
            title: 'เลือกระดับความเผ็ด',
            input: 'select',
            inputOptions: {
                '': 'ไม่ระบุ',
                'ไม่เผ็ด': 'ไม่เผ็ด',
                'เผ็ดน้อย': 'เผ็ดน้อย',
                'เผ็ดกลาง': 'เผ็ดกลาง',
                'เผ็ดมาก': 'เผ็ดมาก',
                'หม่าล่าลิ้นชา': 'หม่าล่าลิ้นชา'
            },
            inputPlaceholder: 'เลือกระดับความเผ็ด',
            showCancelButton: true,
            confirmButtonText: 'เพิ่มลงตะกร้า',
            cancelButtonText: 'ยกเลิก',
            customClass: {
                actions: "my-actions",
                cancelButton: "order-1 right-gap",
                confirmButton: "order-2",
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const spice_level = result.value;
                axios
                    .post("/admin/cart", { id, spice_level, toppings: "" })
                    .then((res) => {
                        setCartUpdated(!cartUpdated);
                        playSound(SuccessSound);
                        toast.success(res?.data?.message);
                    })
                    .catch((err) => {
                        playSound(WarningSound);
                        toast.error(err.response.data.message);
                    });
            }
        });
    }
    function cartEmpty() {
        if (total <= 0) {
            return;
        }
        Swal.fire({
            title: "ต้องการล้างตะกร้าทั้งหมดใช่ไหม?",
            showDenyButton: true,
            confirmButtonText: "ใช่",
            denyButtonText: "ไม่",
            customClass: {
                actions: "my-actions",
                cancelButton: "order-1 right-gap",
                confirmButton: "order-2",
                denyButton: "order-3",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                axios
                    .put("/admin/cart/empty")
                    .then((res) => {
                        setCartUpdated(!cartUpdated);
                        playSound(SuccessSound);
                        toast.success(res?.data?.message);
                    })
                    .catch((err) => {
                        playSound(WarningSound);
                        toast.error(err.response.data.message);
                    });
            } else if (result.isDenied) {
                return;
            }
        });
    }
    function orderCreate() {
        if (total <= 0) {
            return;
        }
        if (!customerId) {
            toast.error("กรุณาเลือกลูกค้า");
            return;
        }
        Swal.fire({
            title: `ยืนยันการปิดบิลนี้ใช่ไหม? <br>ยอดค้างชำระ: ${due}`,
            showDenyButton: true,
            confirmButtonText: "ยืนยัน",
            denyButtonText: "ยกเลิก",
            customClass: {
                actions: "my-actions",
                cancelButton: "order-1 right-gap",
                confirmButton: "order-2",
                denyButton: "order-3",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                axios
                    .put("/admin/order/create", {
                        customer_id: customerId,
                        order_discount: parseFloat(orderDiscount) || 0,
                        applied_rewards: appliedRewards.map(r => r.id),
                        paid: parseFloat(paid) || 0,
                        order_type: orderType,
                        notes: notes,
                    })
                    .then((res) => {
                        setCartUpdated(!cartUpdated);
                        setProductUpdated(!productUpdated);
                        toast.success(res?.data?.message);
                        // window.location.href = `orders/invoice/${res?.data?.order?.id}`;
                        window.location.href = `orders/pos-invoice/${res?.data?.order?.id}`;
                    })
                    .catch((err) => {
                        toast.error(err.response.data.message);
                    });
            } else if (result.isDenied) {
                return;
            }
        });
    }
    return (
        <>
            <div className="card">
                {/* <div class="mt-n5 mb-3 d-flex justify-content-end">
                    <a
                        href="/admin"
                        className="btn bg-gradient-primary mr-2"
                    >
                        แดชบอร์ด
                    </a>
                    <a
                        href="/admin/ordersma"
                        className="btn bg-gradient-primary"
                    >
                        รายการขาย
                    </a>
                </div> */}

                <div className="card-body p-2 p-md-4 pt-0">
                    <div className="row">
                        <div className="col-md-6 col-lg-5 mb-2">
                            <div className="row mb-2">
                                <div className="col-12 mb-2">
                                    <select
                                        className="form-control"
                                        value={orderType}
                                        onChange={(e) => setOrderType(e.target.value)}
                                    >
                                        <option value="dine_in">ทานที่ร้าน</option>
                                        <option value="takeaway">กลับบ้าน</option>
                                        <option value="delivery">เดลิเวอรี่</option>
                                    </select>
                                </div>
                                <div className="col-12 mb-2">
                                    <textarea
                                        className="form-control"
                                        placeholder="หมายเหตุ (ตัวเลือกเพิ่มเติม)"
                                        value={notes}
                                        onChange={(e) => setNotes(e.target.value)}
                                        rows="1"
                                    ></textarea>
                                </div>
                                <div className="col-12">
                                    <CustomerSelect
                                        setCustomerId={setCustomerId}
                                    />
                                </div>
                                {/* <div className="col-6">
                                <form className="form">
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="ค้นหาหรือสแกนบาร์โค้ด…"
                                        value={searchQuery}
                                        onChange={(e) =>
                                            setSearchQuery(e.target.value)
                                        }
                                    />
                                </form>
                            </div> */}
                            </div>
                            <Cart
                                carts={carts}
                                setCartUpdated={setCartUpdated}
                                cartUpdated={cartUpdated}
                            />
                            <div className="card">
                                <div className="card-body">
                                    <div className="row text-bold mb-1">
                                        <div className="col">ยอดก่อนส่วนลด:</div>
                                        <div className="col text-right mr-2">
                                            {total}
                                        </div>
                                    </div>
                                    <div className="row text-bold mb-1">
                                        <div className="col">ส่วนลด:</div>
                                        <div className="col text-right mr-2">
                                            <input
                                                type="number"
                                                className="form-control form-control-sm"
                                                placeholder="กรอกส่วนลด"
                                                min={0}
                                                disabled={total <= 0}
                                                value={orderDiscount}
                                                onChange={(e) => {
                                                    const value =
                                                        e.target.value;
                                                    if (
                                                        parseFloat(value) >
                                                            total ||
                                                        parseFloat(value) < 0
                                                    ) {
                                                        return;
                                                    }
                                                    setOrderDiscount(value);
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className="row text-bold mb-1">
                                        <div className="col">
                                            ปัดเศษเป็นส่วนลด:
                                        </div>
                                        <div className="col text-right mr-2">
                                            <input
                                                type="checkbox"
                                                className="form-control-sm"
                                                disabled={total <= 0}
                                                onChange={(e) => {
                                                    if (e.target.checked) {
                                                        const fractionalPart =
                                                            total % 1;
                                                        setOrderDiscount(
                                                            fractionalPart?.toFixed(
                                                                2
                                                            )
                                                        );
                                                    } else {
                                                        setOrderDiscount(0);
                                                    }
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className="row text-bold mb-1">
                                        <div className="col">โปรโมชั่นที่เลือกได้:</div>
                                        <div className="col text-right mr-2">
                                            <select
                                                className="form-control form-control-sm"
                                                multiple
                                                value={appliedRewards.map(r => r.id)}
                                                onChange={(e) => {
                                                    const selectedOptions = Array.from(e.target.selectedOptions).map(opt => parseInt(opt.value));
                                                    const selected = availableRewards.filter(r => selectedOptions.includes(r.id));
                                                    setAppliedRewards(selected);
                                                }}
                                                disabled={availableRewards.length === 0}
                                                style={{height: '60px'}}
                                            >
                                                {availableRewards.length === 0 && <option disabled>ไม่มีโปรโมชั่น</option>}
                                                {availableRewards.map(r => (
                                                    <option key={r.id} value={r.id}>{r.name}</option>
                                                ))}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="row text-bold mb-1 text-success">
                                        <div className="col">ส่วนลดโปรโมชั่น:</div>
                                        <div className="col text-right mr-2">
                                            {parseFloat(rewardDiscount).toFixed(2)}
                                        </div>
                                    </div>
                                    <div className="row text-bold mb-1">
                                        <div className="col">ยอดสุทธิ:</div>
                                        <div className="col text-right mr-2">
                                            {updateTotal}
                                        </div>
                                    </div>
                                    <div className="row text-bold mb-1">
                                        <div className="col">รับเงิน:</div>
                                        <div className="col text-right mr-2">
                                            <input
                                                type="number"
                                                className="form-control form-control-sm"
                                                placeholder="กรอกยอดรับเงิน"
                                                min={0}
                                                disabled={total <= 0}
                                                value={paid}
                                                onChange={(e) => {
                                                    const value =
                                                        e.target.value;
                                                    if (
                                                        parseFloat(value) < 0 ||
                                                        parseFloat(value) >
                                                            updateTotal
                                                    ) {
                                                        return;
                                                    }
                                                    setPaid(value);
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className="row text-bold">
                                        <div className="col">ค้างชำระ:</div>
                                        <div className="col text-right mr-2">
                                            {due}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col">
                                    <button
                                        onClick={() => cartEmpty()}
                                        type="button"
                                        className="btn bg-gradient-danger btn-block text-white text-bold"
                                    >
                                        ล้างตะกร้า
                                    </button>
                                </div>
                                <div className="col">
                                    <button
                                        onClick={() => {
                                            orderCreate();
                                        }}
                                        type="button"
                                        className="btn bg-gradient-primary btn-block text-white text-bold"
                                    >
                                        ชำระเงิน
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-6 col-lg-7">
                            <div className="row">
                                <div className="input-group mb-2 col-md-6">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                    </div>
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="สแกนหรือกรอกรหัสสินค้า"
                                        value={searchBarcode}
                                        autoFocus
                                        onChange={(e) =>
                                            setSearchBarcode(e.target.value)
                                        }
                                    />
                                </div>
                                <div className="mb-2 col-md-6">
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="ค้นหาชื่อสินค้า"
                                        value={searchQuery}
                                        onChange={(e) =>
                                            setSearchQuery(e.target.value)
                                        }
                                    />
                                </div>
                            </div>
                            <div className="row products-card-container">
                                {products.length > 0 &&
                                    products.map((product, index) => (
                                        <div
                                            onClick={() =>
                                                addProductToCart(product.id)
                                            }
                                            className="col-6 col-md-4 col-lg-3 mb-3"
                                            key={index}
                                            style={{ cursor: "pointer" }}
                                        >
                                            <div className="text-center">
                                                <img
                                                    src={product.image_url || `${fullDomainWithPort}/storage/${product.image}`}
                                                    alt={product.name}
                                                    className="mr-2 img-thumb"
                                                    onError={(e) => {
                                                        e.target.onerror = null;
                                                        e.target.src = `${fullDomainWithPort}/assets/images/demo/no-image.svg`;
                                                    }}
                                                    width={120}
                                                    height={100}
                                                />
                                                <div className="product-details">
                                                    <p className="mb-0 text-bold product-name">
                                                        {product.name} (
                                                        {product.quantity})
                                                    </p>
                                                    <p>
                                                        ราคา:{" "}
                                                        {
                                                            product?.discounted_price
                                                        }
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                            </div>
                            {loading && (
                                <div className="loading-more">
                                    กำลังโหลดสินค้า…
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
            <Toaster position="top-right" reverseOrder={false} />
        </>
    );
}
