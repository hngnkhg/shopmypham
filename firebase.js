import { initializeApp } from "firebase/app";
import { getFirestore } from "firebase/firestore";
import { getAuth } from "firebase/auth";


// Cấu hình Firebase của bạn
const firebaseConfig = {
  apiKey: "AIzaSyCQ60VTocAbAMby-i10_VQfhAGw7oCwAl4",
  authDomain: "shopmypham-a9b48.firebaseapp.com",
  projectId: "shopmypham-a9b48",
  storageBucket: "shopmypham-a9b48.firebasestorage.app",
  messagingSenderId: "114082575044",
  appId: "1:114082575044:web:98a1f4e6c52e799cedcf91",
  measurementId: "G-44R407Z7W5"
};

// ✅ Khởi tạo app - chỉ gọi 1 lần
const app = initializeApp(firebaseConfig);

// ✅ Khai báo `auth` và `db` - chỉ 1 lần mỗi cái
export const auth = getAuth(app);
export const db = getFirestore(app);

