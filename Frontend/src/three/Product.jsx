import { useRef } from "react";
import { useFrame } from "@react-three/fiber";

export default function Product({ productRef }) {
  const localRef = useRef();

  useFrame(() => {
    const mesh = productRef?.current || localRef.current;
    if (!mesh) return; // 🔥 MOST IMPORTANT LINE

    mesh.rotation.y += 0.003;
    mesh.position.y = Math.sin(Date.now() * 0.001) * 0.08;
  });

  return (
    <mesh ref={productRef || localRef} position={[0, -1, 0]}>
      <cylinderGeometry args={[0.6, 0.6, 2.2, 64]} />
      <meshPhysicalMaterial
        color="#e6c7a2"
        metalness={0.7}
        roughness={0.15}
        clearcoat={1}
      />
    </mesh>
  );
}