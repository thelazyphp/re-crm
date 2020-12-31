import Http from './Http'

export function uploadFile (file, onUploadProgress) {
    return new Promise((resolve, reject) => {
        const data = new FormData()
        data.append('file', file)

        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            },
            onUploadProgress
        }

        new Http({ auth: true }).post('/crm/update', data, config)
            .then(response => resolve(response))
            .catch(error => reject(error))
    })
}

export function checkUpdateStatus (contentLocation) {
    return new Promise((resolve, reject) => {
        new Http({ auth: true }).get(contentLocation)
            .then(response => resolve(response))
            .catch(error => reject(error))
    })
}
