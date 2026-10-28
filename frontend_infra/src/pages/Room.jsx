import { useEffect, useState } from "react";
import { api } from "../api";

export default function Room() {
    const [rooms , setRooms] = useState([]);
    const [loading , setLoading] = useState(true);
    const [errors , setErrors] = useState('')

    useEffect(() => {
        document.title ='rooms';
        api('/rooms')
            .then((json) => setRooms(json.data))
            .catch(() => setErrors('Gagal memuat data'))
            .finally(() => setLoading(false))
    } , [])

    if (loading) return <p>loading...</p>
    if (errors) return <p>{errors}</p>
    return(
        <table border={1}>
            <thead><tr><td>Nama</td><td>Gedung</td></tr></thead>
            <tbody>
                {rooms.map((r) => (
                    <tr key={r.id}><td>{r.name}</td><td>{r.building}</td></tr>
                ))}
            </tbody>
        </table>
    );
}