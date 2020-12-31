import Base from './Base'

export default class Feed extends Base {
    get schema () {
        return {
            id: undefined,
            url: undefined,
            generated_at: undefined,
            status: '',
            progress: 0
        }
    }
}
