import React from "react";
import { FaCheck } from "react-icons/fa";
import "../assets/styles/plugins/stepper.css"; // CSS file create karein

const Stepper = ({ currentStep }) => {
  const steps = ["Cart", "Address", "Payment", "Summary"];

  return (
    <div className="stepper-container d-flex justify-content-center align-items-center mb-5">
      {steps.map((step, index) => (
        <React.Fragment key={index}>
          {/* Step Circle */}
          <div className="step-wrapper text-center position-relative">
            <div className={`step-circle ${currentStep > index + 1 ? "completed" : currentStep === index + 1 ? "active" : ""}`}>
              {currentStep > index + 1 ? <FaCheck size={12} /> : index + 1}
            </div>
            <span className={`step-label ${currentStep >= index + 1 ? "active-label" : ""}`}>
              {step}
            </span>
          </div>

          {/* Line between steps */}
          {index < steps.length - 1 && (
            <div className={`step-line ${currentStep > index + 1 ? "line-completed" : ""}`}></div>
          )}
        </React.Fragment>
      ))}
    </div>
  );
};

export default Stepper;