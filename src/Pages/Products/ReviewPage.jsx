
import React from 'react';
import { CiStar } from "react-icons/ci";
import { FaChevronDown } from "react-icons/fa";
import { FaCamera } from "react-icons/fa";
import image from '../../assets/images/1256-removebg-preview.png';

const ReviewPage = () => {
    return (
        <>
            <div className="max-w-6xl mx-auto p-5 mt-5 font-sans text-slate-800 bg-white">

                <h3 className="text-xl font-bold mb-6">
                    Love Beauty & Planet Onion, Black Seed & Patchouli Hair Fall Control Sulphate Free Shampoo - All Reviews
                </h3>

                <div className="d-flex row">
                    <div className="col-lg-8">
                        <div className="bg-pink-50 border border-pink-100 rounded-lg p-4 flex items-center gap-4 mb-8">
                            <div className="bg-white p-2 rounded-full border border-pink-200">
                                <span className="text-pink-500 text-xl">📋 Nykaa is committed to showing genuine and verified reviews.</span>
                            </div>
                        </div>

                        <div className="d-flex items-start justify-between border-b gap-5 pb-8 mb-6">
                            <div className="d-flex items-center gap-4 border-end pe-5">
                                <div className="text-5xl font-bold">4.4<span className="text-2xl text-gray-400 font-normal">/5</span></div>
                                <div>
                                    <div className="font-bold text-sm">Overall Rating</div>
                                    <div className="text-xs text-gray-500">102508 verified ratings</div>
                                </div>
                            </div>

                            <div className="text-center pl-8 border-l border-gray-200">
                                <p className="text-xs text-gray-600 mb-2">Write a review and win 100 reward points !</p>
                                <button className="border text-pink-500 px-6 py-2 text-white rounded font-semibold hover:bg-pink-50 transition" style={{background: '#EA4C71'}}>
                                    Write Review
                                </button>
                            </div>
                        </div>

                        <div className="mb-8">
                            <h3 className="font-bold text-sm mb-3">Refine Reviews By</h3>
                            <div className="flex flex-wrap gap-2 mb-4">
                                <button className="px-4 py-1.5 rounded text-sm bg-transparent" style={{borderColor: '#EA4C71'}}>Verified Buyers</button>
                                <button className="px-4 py-1.5 rounded bg-transparent ms-3" style={{borderColor: '#EA4C71'}}>With Images</button>
                                {/* {[5, 4, 3, 2, 1].map(star => (
                <button key={CiStar} className="px-4 py-1.5 rounded-full border border-gray-300 text-gray-600 text-sm">{CiStar} Star</button>
              ))} */}
                            </div>
                            <button className="flex items-center gap-2 border border-gray-300 px-4 py-2 rounded text-sm font-semibold">
                                {/* <span className="rotate-90">|||</span> Most Useful <FaChevronDown size={16} /> */}
                            </button>
                        </div>

                        {/* Individual Review */}
                        <div className="border-t pt-6">
                            <div className="flex gap-4">
                                <div className="w-12 h-12 rounded-full bg-pink-100 flex items-center justify-center text-pink-400 font-bold text-xl">N</div>
                                <div className="flex-grow">
                                    <div className="flex justify-between items-start">
                                        <div>
                                            <span className="font-bold text-sm block">Nutan Sangma</span>
                                            <span className="text-pink-500 text-xs flex items-center gap-1">✓ Verified Buyers</span>
                                        </div>
                                        <span className="text-gray-400 text-xs">19/04/2023</span>
                                    </div>

                                    <div className="mt-3 flex items-center gap-2">
                                        <span className="bg-green-700 text-white text-[10px] px-1.5 py-0.5 rounded flex items-center gap-0.5 font-bold">5</span>
                                    </div>

                                    <p className="font-bold text-sm mt-2">" Work Amazing."</p>
                                    <p className="text-sm text-gray-600 mt-1 leading-relaxed">
                                        This shampoo is wonderful. Helped me a lot to manage my hair. Also, strengthen my roots to prevention from hair fall. Now I'm suggesting this shampoo to everyone in my family...<span className="text-gray-900 font-semibold cursor-pointer">Read More</span>
                                    </p>

                                    <div className="flex gap-2 mt-4">
                                        {[1, 2, 3, 4].map((i) => (
                                            <div key={i} className="w-16 h-20 bg-gray-200 rounded overflow-hidden">
                                                <img src={`https://picsum.photos/seed/${i + 10}/100/150`} alt="review" className="w-full h-full object-cover" />
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Right Section: Product Sticky Card */}
                    <div className=" col-lg-4">
                        <div className="border border-gray-100 rounded-lg p-4 shadow-sm sticky top-4">
                            <div className="relative aspect-[3/4] mb-4 bg-gray-50 rounded">
                                <img
                                    src={image}
                                    alt="product"
                                    className="w-full h-full object-contain mix-blend-multiply p-4"
                                />
                            </div>
                            <div className="text-center">
                                <h4 className="text-xs font-medium text-gray-800 mb-2 leading-tight">
                                    Love Beauty & Planet Onion, Black Seed &
                                </h4>
                                <div className="flex items-center justify-center gap-2 mb-4">
                                    <span className="text-gray-400 line-through text-xs">₹658</span>
                                    <span className="font-bold text-sm">₹559</span>
                                    <span className="text-green-600 font-bold text-xs">15% Off</span>
                                </div>
                                <button className="w-full bg-[#ec3d63] text-white py-3 rounded font-bold" style={{background: '#EA4C71'}}>
                                    Add to Bag
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default ReviewPage;