import { useState } from "react";
import { useNavigate } from "react-router-dom";

export default function Login() {
    const [form , setform] = useState({email : '' , password : ''})
    const [errors , setErrors] = useState('');
    const navigate = useNavigate()

    
}