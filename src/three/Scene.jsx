import { useThree } from "@react-three/fiber";
import { useEffect, useRef } from "react";
import Lights from "./Lights";
import Product from "./Product";
import { introAnimation } from "../animations/gsapTimeline";

export default function Scene() {
  const { camera } = useThree();
  const productRef = useRef();

  useEffect(() => {
    if (productRef.current) {
      introAnimation(camera, productRef.current);
    }
  }, []);

  return (
    <>
      <Lights />
      <Product productRef={productRef} />
    </>
  );
}