export default function Lights() {
    return (
      <>
        <ambientLight intensity={0.4} />
        <directionalLight position={[5, 5, 5]} intensity={1.8} />
        <directionalLight position={[-5, 2, 5]} intensity={0.8} color="#ffe6d6" />
        <directionalLight position={[0, 5, -5]} intensity={1} />
      </>
    );
  }