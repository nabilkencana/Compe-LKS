const API = "http://127.0.0.1:8000";

export async function api(path ,option =[]) {
    const token = localStorage.getItem('token');
    const isForm = option.body instanceof FormData;

    const res = await fetch(API + path , {
        ...options,
        headers : {
            Accept : 'application/json',
            ...(option.body && !isForm?{"Content-Type" : "application/json"}: {}),
            ...(token ? {Authorization : 'Bearer' + token}: {}),
        }
    });

    const data = res.status === 204 ? null : await res.json().catch(() => null);
    if (!res.ok) throw {status : res.status , data};
    return data 
}