import useAgent from './useAgent';

class Drift {
    store(payload: DriftStorePayload) {
        return useAgent<DriftStoreResponse>('drift/store').post(payload);
    }

    index(payload: DriftIndexPayload) {
        const entrypoint = 'drift/index';

        const params = new URLSearchParams();
        params.append('page', payload.page.toString());

        const url = entrypoint + '?' + params.toString();

        return useAgent<DriftIndexResponse>(url).get();
    }

    show(id: number) {
        return useAgent<DriftShowResponse>('drift/show/' + id).get();
    }

    update(payload: DriftUpdatePayload) {
        return useAgent<DriftStoreResponse>('drift/update').post(payload);
    }

    destroy(id: number) {
        return useAgent<DriftDestroyResponse>('drift/destroy/' + id).get();
    }
}

export default new Drift();
