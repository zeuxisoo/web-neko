import useAgent from './useAgent';

class Tag {
    all() {
        return useAgent<PulseTagResponse>('pulse/tag/all').get();
    }
}

export default {
    get tag() {
        return new Tag();
    },
};
